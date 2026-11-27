<?php

namespace App\Modules\Booking\Mail;

use App\Modules\Booking\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepartureReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Molofy Trip is Tomorrow - '.$this->booking->reference
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.departure-reminder'
        );
    }
}