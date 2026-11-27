<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>Your booking is confirmed!</h2>

    <p>Hello {{ $booking->customer->name }},</p>

    <p>
        Thank you for booking with Molofy.
        Your booking has been successfully confirmed.
    </p>

    <h3>Booking Details</h3>

    <p>
        <strong>Booking Reference:</strong>
        {{ $booking->reference }}
    </p>

    <p>
        <strong>Trip:</strong>
        {{ $booking->departure->trip->name }}
    </p>

    <p>
        <strong>Departure:</strong>
        {{ $booking->departure->departure_at
            ->timezone(config('molofy.timezone'))
            ->format('d M Y, H:i') }}
    </p>

    <p>
        <strong>Boat:</strong>
        {{ $booking->departure->boat->name }}
    </p>

    <p>
        <strong>Meeting Point:</strong>
        {{ $booking->departure->meeting_point }}
    </p>

    @if($booking->departure->meeting_instructions)
        <p>
            <strong>Instructions:</strong>
            {{ $booking->departure->meeting_instructions }}
        </p>
    @endif

    @if($booking->departure->operators->isNotEmpty())
        <p>
            <strong>Assigned Operator(s):</strong>
            {{ $booking->departure->operators->pluck('name')->join(', ') }}
        </p>
    @endif

    <h3>Guest Details</h3>

    <p>Adults: {{ $booking->adults }}</p>
    <p>Children: {{ $booking->children }}</p>
    <p>Infants: {{ $booking->infants }}</p>
    <p>Total Guests: {{ $booking->total_seats }}</p>

    <h3>Payment Summary</h3>

    <p>
        Booking Total:
        €{{ number_format($booking->total_amount, 2) }}
    </p>

    <p>
        Deposit Amount:
        €{{ number_format($booking->deposit_amount, 2) }}
    </p>

    <p>
        Remaining Amount:
        €{{ number_format($booking->remaining_amount, 2) }}
    </p>

    <p>
        No online payment has been collected at this stage.
    </p>

    <p>
        <a href="{{ route('booking.confirmation', $booking->confirmation_token) }}">
            View Booking Confirmation and QR Code
        </a>
    </p>

    <p>
        Please keep your booking reference and QR code ready
        when arriving for your trip.
    </p>

    <p>Thank you,<br>Molofy Team</p>

</body>
</html>