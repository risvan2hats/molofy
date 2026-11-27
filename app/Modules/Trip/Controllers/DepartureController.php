<?php

namespace App\Modules\Trip\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Boat\Models\Boat;
use App\Modules\Trip\Models\Departure;
use App\Modules\Trip\Models\Trip;
use App\Modules\Trip\Requests\StoreDepartureRequest;
use App\Modules\Trip\Requests\UpdateDepartureRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DepartureController extends Controller
{
    public function index(): Response
    {
        $departures = Departure::query()
            ->with(['trip', 'boat'])
            ->latest('departure_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Departure/Pages/Index', [
            'departures' => $departures,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Departure/Pages/Form', [
            'departure' => null,
            'trips' => Trip::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'duration_minutes']),
            'boats' => Boat::query()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(
        StoreDepartureRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $this->validateAvailability($data);

        Departure::create($data);

        return redirect()
            ->route('departures.index')
            ->with('success', 'Departure created successfully.');
    }

    public function edit(Departure $departure): Response
    {
        return Inertia::render('Departure/Pages/Form', [
            'departure' => $departure,
            'trips' => Trip::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'duration_minutes']),
            'boats' => Boat::query()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(
        UpdateDepartureRequest $request,
        Departure $departure
    ): RedirectResponse {
        $data = $request->validated();

        $this->validateAvailability($data, $departure);

        $departure->update($data);

        return redirect()
            ->route('departures.index')
            ->with('success', 'Departure updated successfully.');
    }

    public function destroy(Departure $departure): RedirectResponse
    {
        if ($departure->status !== 'cancelled') {
            return back()->with(
                'error',
                'Only cancelled departures can be deleted.'
            );
        }

        $departure->delete();

        return redirect()
            ->route('departures.index')
            ->with('success', 'Departure deleted successfully.');
    }

    private function validateAvailability(
        array $data,
        ?Departure $departure = null
    ): void {
        $boat = Boat::findOrFail($data['boat_id']);

        if ($boat->is_active === false) {
            abort(422, 'The selected boat is inactive.');
        }

        $trip = Trip::findOrFail($data['trip_id']);

        if (!$trip->is_active) {
            abort(422, 'The selected trip is inactive.');
        }

        $start = $data['departure_at'];
        $end = $data['arrival_at']
            ?? now()->parse($start)->addMinutes($trip->duration_minutes);

        $overlapping = Departure::query()
            ->where('boat_id', $boat->id)
            ->where('status', 'scheduled')
            ->when($departure, function ($query) use ($departure) {
                $query->where('id', '!=', $departure->id);
            })
            ->where('departure_at', '<', $end)
            ->whereRaw(
                'COALESCE(arrival_at, departure_at) > ?',
                [$start]
            )
            ->exists();

        if ($overlapping) {
            abort(422, 'The selected boat already has a departure during this time.');
        }
    }
}
