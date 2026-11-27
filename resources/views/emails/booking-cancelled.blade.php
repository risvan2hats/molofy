<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Cancelled</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>Your booking has been cancelled</h2>

    <p>Hello {{ $booking->customer->name }},</p>

    <p>Your booking has been successfully cancelled.</p>

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

    <h3>Refund Details</h3>

    @if($booking->cancellation_refund_amount > 0)
        <p>
            Your booking is eligible for a deposit refund of
            <strong>
                €{{ number_format($booking->cancellation_refund_amount, 2) }}
            </strong>.
        </p>

        <p>
            The refund will be processed manually.
        </p>
    @else
        <p>
            This cancellation is not eligible for a deposit refund
            under our cancellation policy.
        </p>
    @endif

    <p>Thank you,<br>Molofy Team</p>

</body>
</html>