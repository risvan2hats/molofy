<?php

use App\Modules\Trip\Controllers\TripController;
use App\Modules\Trip\Controllers\DepartureController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('admin/trips')
    ->name('trips.')
    ->controller(TripController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{trip}/edit', 'edit')->name('edit');
        Route::put('/{trip}', 'update')->name('update');
        Route::delete('/{trip}', 'destroy')->name('destroy');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('admin/departures')
    ->name('departures.')
    ->controller(DepartureController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{departure}/edit', 'edit')->name('edit');
        Route::put('/{departure}', 'update')->name('update');
        Route::delete('/{departure}', 'destroy')->name('destroy');
    });
