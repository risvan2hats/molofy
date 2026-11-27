<?php

use App\Modules\Booking\Controllers\PublicBookingController;
use App\Modules\Booking\Controllers\BookingController;
use App\Modules\Booking\Controllers\BookingAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicBookingController::class, 'index'])
    ->name('booking.home');

Route::get('/trips/{trip:slug}', [PublicBookingController::class, 'show'])
    ->name('booking.trips.show');

Route::get('/departures/{departure}/availability', [
    PublicBookingController::class,
    'checkAvailability',
])->name('booking.departures.availability');

Route::get('/departures/{departure}/book', [BookingController::class, 'create'])
    ->name('booking.create');

Route::post('/departures/{departure}/book', [BookingController::class, 'store'])
    ->name('booking.store');

Route::get('/booking/confirmation/{token}', [BookingController::class, 'confirmation'])
    ->name('booking.confirmation');

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/bookings', [BookingAdminController::class, 'index'])
            ->name('bookings.index');

        Route::get('/bookings/{booking}', [BookingAdminController::class, 'show'])
            ->name('bookings.show');

        Route::post('/bookings/{booking}/cancel', [BookingAdminController::class, 'cancel'])
            ->name('bookings.cancel');
    });


Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/bookings', [BookingAdminController::class, 'index'])
            ->name('bookings.index');

        Route::get('/bookings/{booking}', [BookingAdminController::class, 'show'])
            ->name('bookings.show');

        Route::post('/bookings/{booking}/cancel', [BookingAdminController::class, 'cancel'])
            ->name('bookings.cancel');

        Route::post('/bookings/{booking}/refund', [BookingAdminController::class, 'processRefund'])
            ->name('bookings.refunds.process');
    });

Route::get('/booking/qr/{token}', [BookingController::class, 'qr'])
    ->name('booking.qr');