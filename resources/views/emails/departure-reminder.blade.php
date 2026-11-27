<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Departure Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>Your trip is coming up!</h2>

    <p>Hello {{ $booking->customer->name }},</p>

    <p>
        This is a reminder that your Molofy trip is scheduled
        for tomorrow.
    </p>

    <h3>Trip Details</h3>

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

    <p>
        Please arrive at the meeting point on time
        and keep your booking QR code ready.
    </p>

    <p>
        <a href="{{ route('booking.confirmation', $booking->confirmation_token) }}">
            View Booking and QR Code
        </a>
    </p>

    <p>See you soon!<br>Molofy Team</p>

</body>
</html>