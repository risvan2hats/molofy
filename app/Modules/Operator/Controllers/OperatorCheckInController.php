<?php

namespace App\Modules\Operator\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Models\BookingGuest;
use App\Modules\Operator\Models\Operator;
use App\Modules\Trip\Models\Departure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class OperatorCheckInController extends Controller
{
    public function departures(Request $request): Response
    {
        $operator = $request->attributes->get('operator');

        $departures = $operator->departures()
            ->with(['trip', 'boat'])
            ->where('status', 'scheduled')
            ->where('departure_at', '>', now(config('molofy.timezone')))
            ->orderBy('departure_at')
            ->get();

        return Inertia::render('Operator/Departures', [
            'departures' => $departures,
            'operator' => [
                'name' => $operator->name,
            ],
        ]);
    }

    public function scanPage(
        Request $request,
        Departure $departure
    ): Response {
        $this->ensureAssigned($request, $departure);

        return Inertia::render('Operator/Scan', [
            'departure' => $departure->load(['trip', 'boat']),
        ]);
    }

    public function findBooking(
        Request $request,
        Departure $departure
    ): JsonResponse {
        $this->ensureAssigned($request, $departure);

        $data = $request->validate([
            'token' => ['required', 'string', 'size:64'],
        ]);

        $booking = Booking::query()
            ->with(['customer', 'guests'])
            ->where('confirmation_token', $data['token'])
            ->where('departure_id', $departure->id)
            ->first();

        if (!$booking) {
            throw ValidationException::withMessages([
                'token' => 'This booking does not belong to the selected departure.',
            ]);
        }

        if ($booking->status !== 'confirmed') {
            throw ValidationException::withMessages([
                'token' => 'This booking is not confirmed.',
            ]);
        }

        return response()->json([
            'booking' => [
                'id' => $booking->id,
                'reference' => $booking->reference,
                'customer' => $booking->customer->name,
                'total_seats' => $booking->total_seats,
                'guests' => $booking->guests->map(fn ($guest) => [
                    'id' => $guest->id,
                    'type' => $guest->guest_type,
                    'number' => $guest->guest_number,
                    'boarded_at' => $guest->boarded_at,
                ]),
            ],
        ]);
    }

    public function boardGuest(
        Request $request,
        Departure $departure,
        BookingGuest $guest
    ): JsonResponse {
        $operator = $request->attributes->get('operator');

        $this->ensureAssigned($request, $departure);

        $guest = DB::transaction(function () use (
            $guest,
            $departure,
            $operator
        ) {
            $guest = BookingGuest::query()
                ->whereKey($guest->id)
                ->lockForUpdate()
                ->with('booking')
                ->firstOrFail();

            if ($guest->booking->departure_id !== $departure->id) {
                abort(404);
            }

            if ($guest->booking->status !== 'confirmed') {
                throw ValidationException::withMessages([
                    'guest' => 'This booking is no longer confirmed.',
                ]);
            }

            if ($guest->boarded_at) {
                throw ValidationException::withMessages([
                    'guest' => 'This guest has already boarded.',
                ]);
            }

            $guest->update([
                'boarded_at' => now(config('molofy.timezone')),
                'checked_in_by' => $operator->id,
            ]);

            return $guest->fresh();
        });

        return response()->json([
            'message' => 'Guest successfully checked in.',
            'guest' => [
                'id' => $guest->id,
                'boarded_at' => $guest->boarded_at,
            ],
        ]);
    }

    private function ensureAssigned(
        Request $request,
        Departure $departure
    ): void {
        $operator = $request->attributes->get('operator');

        abort_unless(
            $operator->departures()
                ->where('departures.id', $departure->id)
                ->exists(),
            403,
            'You are not assigned to this departure.'
        );
    }
}
