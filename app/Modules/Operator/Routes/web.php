<?php

use App\Modules\Operator\Controllers\OperatorAssignmentController;
use App\Modules\Operator\Controllers\OperatorAuthController;
use App\Modules\Operator\Controllers\OperatorCheckInController;
use App\Modules\Operator\Http\Middleware\EnsureOperatorAuthenticated;
use Illuminate\Support\Facades\Route;

Route::prefix('operator')->name('operator.')->group(function () {
    Route::get('/login', [OperatorAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [OperatorAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');

    Route::middleware(EnsureOperatorAuthenticated::class)->group(function () {
        Route::post('/logout', [OperatorAuthController::class, 'logout'])->name('logout');

        Route::get('/departures', [OperatorCheckInController::class, 'departures'])->name('departures');

        Route::get('/departures/{departure}/scan', [OperatorCheckInController::class, 'scanPage'])->name('scan');

        Route::post('/departures/{departure}/scan', [OperatorCheckInController::class, 'findBooking'])->name('scan.booking');

        Route::post('/departures/{departure}/guests/{guest}/board', [OperatorCheckInController::class, 'boardGuest'])->name('guest.board');
    });
});

Route::middleware(['auth', 'verified'])
    ->prefix('admin/operators')
    ->name('admin.operators.')
    ->group(function () {
        Route::get('/', [OperatorAssignmentController::class, 'index'])
            ->name('index');

        Route::put('/{operator}/departures', [OperatorAssignmentController::class, 'sync'])
            ->name('departures.sync');
    });
