<?php

namespace App\Modules\Booking\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Trip\Models\Departure;
use App\Modules\Trip\Models\Trip;
use App\Modules\Booking\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicBookingController extends Controller
{
    public function index(): Response
    {
        $trips = Trip::query()
            ->where('is_active', true)
            ->latest()
            ->get();

        return Inertia::render('Booking/Index', [
            'trips' => $trips,
        ]);
    }

    public function show(Trip $trip): Response
    {
        abort_unless($trip->is_active, 404);

        $departures = Departure::query()
            ->with('boat')
            ->where('trip_id', $trip->id)
            ->where('status', 'scheduled')
            ->where('departure_at', '>', now('Europe/Rome'))
            ->orderBy('departure_at')
            ->get()
            ->map(function ($departure) {
                return [
                    'id' => $departure->id,
                    'departure_at' => $departure->departure_at,
                    'arrival_at' => $departure->arrival_at,
                    'meeting_point' => $departure->meeting_point,
                    'meeting_instructions' => $departure->meeting_instructions,
                    'boat' => [
                        'name' => $departure->boat->name,
                        'capacity' => $departure->boat->capacity,
                    ],
                ];
            });

        return Inertia::render('Booking/Show', [
            'trip' => $trip,
            'departures' => $departures,
        ]);
    }

    public function checkAvailability(Request $request, Departure $departure)
    {
        $departure->load(['trip', 'boat']);

        abort_unless(
            $departure->status === 'scheduled'
            && $departure->trip->is_active,
            404
        );

        $capacity = (int) $departure->boat->capacity;

        $bookedSeats = (int) Booking::query()
            ->where('departure_id', $departure->id)
            ->where('status', 'confirmed')
            ->sum('total_seats');

        return response()->json([
            'capacity' => $capacity,
            'booked_seats' => $bookedSeats,
            'available_seats' => max(0, $capacity - $bookedSeats),
        ]);
    }
}
