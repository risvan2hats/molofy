<?php

namespace App\Modules\Booking\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Booking\Mail\BookingCancelledMail;
use App\Modules\Booking\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class BookingAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $bookings = Booking::query()
            ->with(['customer', 'departure.trip', 'departure.boat'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('reference', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Booking/Pages/Index', [
            'bookings' => $bookings,
            'filters' => $request->only('search', 'status'),
        ]);
    }

    public function show(Booking $booking): Response
    {
        $booking->load([
            'customer',
            'departure.trip',
            'departure.boat',
        ]);

        return Inertia::render('Booking/Pages/Show', [
            'booking' => $booking,
            'refundableAmount' => $this->getRefundableAmount($booking),
        ]);
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking) {
            $booking = Booking::query()
                ->whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($booking->status !== 'confirmed') {
                abort(422, 'Only confirmed bookings can be cancelled.');
            }

            $refundableAmount = $this->getRefundableAmount($booking);

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(config('molofy.timezone')),
                'cancellation_refund_amount' => $refundableAmount,
                'refund_status' => $refundableAmount > 0
                    ? 'pending'
                    : 'not_eligible',
            ]);
        });

        $booking->refresh()->load([
            'customer',
            'departure.trip',
            'departure.boat',
        ]);

        try {
            Mail::to($booking->customer->email)
                ->send(new BookingCancelledMail($booking));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking cancelled successfully.');
    }

    public function processRefund(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking) {
            $booking = Booking::query()
                ->whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($booking->status !== 'cancelled') {
                abort(422, 'Only cancelled bookings can be refunded.');
            }

            if (
                $booking->cancellation_refund_amount <= 0 ||
                $booking->refund_status !== 'pending'
            ) {
                abort(422, 'This booking does not have a pending refund.');
            }

            $booking->update([
                'refund_status' => 'processed',
                'refund_processed_at' => now(config('molofy.timezone')),
                'refund_processed_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Refund marked as processed successfully.');
    }

    private function getRefundableAmount(Booking $booking): float
    {
        $departureAt = Carbon::parse(
            $booking->departure()->value('departure_at'),
            config('molofy.timezone')
        );

        $cancellationDeadline = $departureAt->copy()->subHours(24);

        if (now(config('molofy.timezone'))->lessThanOrEqualTo($cancellationDeadline)) {
            return (float) $booking->deposit_amount;
        }

        return 0;
    }
}
