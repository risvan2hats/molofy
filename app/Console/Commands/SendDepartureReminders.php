<?php

namespace App\Console\Commands;

use App\Modules\Booking\Mail\DepartureReminderMail;
use App\Modules\Booking\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDepartureReminders extends Command
{
    protected $signature = 'molofy:send-departure-reminders';

    protected $description = 'Send booking reminders 24 hours before departure';

    public function handle(): int
    {
        $now = now(config('molofy.timezone'));

        $bookings = Booking::query()
            ->where('status', 'confirmed')
            ->whereNull('reminder_sent_at')
            ->whereHas('departure', function ($query) use ($now) {
                $query
                    ->where('departure_at', '>', $now)
                    ->where('departure_at', '<=', $now->copy()->addHours(24));
            })
            ->with([
                'customer',
                'departure.trip',
                'departure.boat',
                'departure.operators',
            ])
            ->get();

        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->customer->email)
                    ->send(new DepartureReminderMail($booking));

                $booking->update([
                    'reminder_sent_at' => $now,
                ]);

                $this->info("Reminder sent: {$booking->reference}");
            } catch (\Throwable $exception) {
                report($exception);

                $this->error("Failed: {$booking->reference}");
            }
        }

        return self::SUCCESS;
    }
}