<?php

namespace App\Modules\Trip\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Trip\Models\Trip;
use App\Modules\Trip\Requests\StoreTripRequest;
use App\Modules\Trip\Requests\UpdateTripRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TripController extends Controller
{
    public function index(): Response
    {
        $trips = Trip::query()
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Trip/Pages/Index', [
            'trips' => $trips,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Trip/Pages/Form', [
            'trip' => null,
        ]);
    }

    public function store(StoreTripRequest $request): RedirectResponse
    {
        Trip::create($request->validated());

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trip created successfully.');
    }

    public function edit(Trip $trip): Response
    {
        return Inertia::render('Trip/Pages/Form', [
            'trip' => $trip,
        ]);
    }

    public function update(
        UpdateTripRequest $request,
        Trip $trip
    ): RedirectResponse {
        $trip->update($request->validated());

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        if ($trip->departures()->exists()) {
            return back()->with(
                'error',
                'This trip has departures and cannot be deleted.'
            );
        }

        $trip->delete();

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trip deleted successfully.');
    }
}