<?php

namespace App\Modules\Booking\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Models\Customer;
use App\Modules\Booking\Requests\StoreBookingRequest;
use App\Modules\Trip\Models\Departure;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;

use App\Modules\Booking\Mail\BookingConfirmedMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function create(Departure $departure): Response
    {
        $departure->load(['trip', 'boat']);

        abort_unless(
            $departure->status === 'scheduled'
            && $departure->trip->is_active,
            404
        );

        $this->ensureBookingIsOpen($departure);

        $bookedSeats = $this->getBookedSeats($departure);

        return Inertia::render('Booking/Create', [
            'departure' => $this->formatDeparture($departure),
            'availableSeats' => max(
                0,
                (int) $departure->boat->capacity - $bookedSeats
            ),
            'depositPercentage' => config('molofy.deposit_percentage'),
        ]);
    }

    public function store(
        StoreBookingRequest $request,
        Departure $departure
    ): RedirectResponse {
        $data = $request->validated();

        $booking = DB::transaction(function () use ($data, $departure) {
            $departure = Departure::query()
                ->whereKey($departure->id)
                ->lockForUpdate()
                ->with(['trip', 'boat'])
                ->firstOrFail();

            abort_unless(
                $departure->status === 'scheduled'
                && $departure->trip->is_active,
                404
            );

            $this->ensureBookingIsOpen($departure);

            $totalSeats = (int) $data['adults']
                + (int) $data['children']
                + (int) $data['infants'];

            $bookedSeats = $this->getBookedSeats($departure);
            $availableSeats = (int) $departure->boat->capacity - $bookedSeats;

            if ($totalSeats > $availableSeats) {
                throw ValidationException::withMessages([
                    'adults' => "Only {$availableSeats} seats are currently available.",
                ]);
            }

            $customer = Customer::updateOrCreate(
                ['email' => $data['customer_email']],
                [
                    'name' => $data['customer_name'],
                    'phone' => $data['customer_phone'],
                ]
            );

            $totalAmountInCents =
                ((int) $data['adults'] * $this->toCents($departure->trip->price_adult))
                + ((int) $data['children'] * $this->toCents($departure->trip->price_child))
                + ((int) $data['infants'] * $this->toCents($departure->trip->price_infant));

            $depositPercentage = (float) config('molofy.deposit_percentage', 20);

            $depositInCents = (int) round(
                $totalAmountInCents * $depositPercentage / 100
            );

            $remainingInCents = $totalAmountInCents - $depositInCents;
           
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'departure_id' => $departure->id,
                'reference' => 'MOL-' . now()->format('ymd') . '-' . Str::upper(Str::random(6)),
                'confirmation_token' => Str::random(64),
                'adults' => $data['adults'],
                'children' => $data['children'],
                'infants' => $data['infants'],
                'total_seats' => $totalSeats,
                'total_amount' => $totalAmountInCents / 100,
                'deposit_amount' => $depositInCents / 100,
                'remaining_amount' => $remainingInCents / 100,
                'status' => 'confirmed',
                'payment_status' => 'pending',
            ]);

            $guests = [];

            foreach ([
                'adult' => (int) $data['adults'],
                'child' => (int) $data['children'],
                'infant' => (int) $data['infants'],
            ] as $type => $count) {
                for ($number = 1; $number <= $count; $number++) {
                    $guests[] = [
                        'guest_type' => $type,
                        'guest_number' => $number,
                    ];
                }
            }

            $booking->guests()->createMany($guests);

            return $booking;

        });

        $booking->load([
            'customer',
            'departure.trip',
            'departure.boat',
            'departure.operators',
        ]);

        // try {
        //     Mail::to($booking->customer->email)
        //         ->send(new BookingConfirmedMail($booking));
        // } catch (\Throwable $exception) {
        //     report($exception);
        // }

        return redirect()->route('booking.confirmation', [
            'token' => $booking->confirmation_token,
        ]);
    }

    public function confirmation(string $token): Response
    {
        $booking = Booking::query()
            ->with(['customer', 'departure.trip', 'departure.boat'])
            ->where('confirmation_token', $token)
            ->firstOrFail();

        return Inertia::render('Booking/Confirmation', [
            'booking' => $booking,
        ]);
    }

    private function getBookedSeats(Departure $departure): int
    {
        return (int) Booking::query()
            ->where('departure_id', $departure->id)
            ->where('status', 'confirmed')
            ->sum('total_seats');
    }

    private function ensureBookingIsOpen(Departure $departure): void
    {
        $departureDate = Carbon::parse(
            $departure->departure_at,
            config('molofy.timezone')
        );

        $cutoff = $departureDate->copy()->subDay()->endOfDay();

        if (now(config('molofy.timezone'))->greaterThan($cutoff)) {
            abort(403, 'Bookings for this departure are closed.');
        }
    }

    private function toCents($amount): int
    {
        return (int) round((float) $amount * 100);
    }

    private function formatDeparture(Departure $departure): array
    {
        return [
            'id' => $departure->id,
            'departure_at' => $departure->departure_at,
            'arrival_at' => $departure->arrival_at,
            'meeting_point' => $departure->meeting_point,
            'meeting_instructions' => $departure->meeting_instructions,
            'boat' => [
                'name' => $departure->boat->name,
                'capacity' => $departure->boat->capacity,
            ],
            'trip' => [
                'name' => $departure->trip->name,
                'price_adult' => $departure->trip->price_adult,
                'price_child' => $departure->trip->price_child,
                'price_infant' => $departure->trip->price_infant,
            ],
        ];
    }

    public function qr(string $token)
    {
        $booking = Booking::query()
            ->where('confirmation_token', $token)
            ->where('status', 'confirmed')
            ->firstOrFail();

        $qrCode = new QrCode(
            data: $booking->confirmation_token,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = (new SvgWriter())->write($qrCode);

        return response($result->getString())
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'private, no-store')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}
