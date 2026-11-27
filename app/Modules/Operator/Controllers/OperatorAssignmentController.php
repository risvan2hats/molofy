<?php

namespace App\Modules\Operator\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operator\Models\Operator;
use App\Modules\Trip\Models\Departure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OperatorAssignmentController extends Controller
{
    public function index(): Response
    {
        $operators = Operator::query()
            ->with('departures:id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $departures = Departure::query()
            ->with(['trip:id,name', 'boat:id,name'])
            ->where('status', '!=', 'cancelled')
            ->orderBy('departure_at')
            ->get(['id', 'trip_id', 'boat_id', 'departure_at', 'meeting_point', 'status']);

        return Inertia::render('Operator/AssignDepartures', [
            'operators' => $operators,
            'departures' => $departures,
        ]);
    }

    public function sync(Request $request, Operator $operator): RedirectResponse
    {
        $validated = $request->validate([
            'departure_ids' => ['nullable', 'array'],
            'departure_ids.*' => ['integer', 'exists:departures,id'],
        ]);

        $operator->departures()->sync($validated['departure_ids'] ?? []);

        return back()->with('success', 'Departures assigned to operator successfully.');
    }
}
