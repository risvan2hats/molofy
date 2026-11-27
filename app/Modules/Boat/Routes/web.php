<?php

use App\Modules\Boat\Controllers\BoatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin/boats')
    ->name('boats.')
    ->group(function () {

        Route::get('/', [BoatController::class, 'index'])
            ->name('index');

        Route::get('/create', [BoatController::class, 'create'])
            ->name('create');

        Route::post('/', [BoatController::class, 'store'])
            ->name('store');

        Route::get('/{boat}', [BoatController::class, 'show'])
            ->name('show');

        Route::get('/{boat}/edit', [BoatController::class, 'edit'])
            ->name('edit');

        Route::put('/{boat}', [BoatController::class, 'update'])
            ->name('update');

        Route::delete('/{boat}', [BoatController::class, 'destroy'])
            ->name('destroy');
    });