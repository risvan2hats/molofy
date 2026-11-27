<?php

namespace App\Modules\Boat\Controllers;

use App\Common\Controllers\BaseController;
use App\Modules\Boat\Models\Boat;
use App\Modules\Boat\Repositories\BoatRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoatController extends BaseController
{
    public function __construct(
        protected BoatRepository $boatRepository
    ) {}

    /**
     * Display boats.
     */
    public function index(Request $request): Response
    {
        $boats = $this->boatRepository->paginate(
            $request->all()
        );

        return Inertia::render('Modules/Boat/Pages/Index', [
            'boats' => $boats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Modules/Boat/Pages/Form', [
            'boat' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive,maintenance'],
        ]);

        Boat::create($validated);

        return redirect()
            ->route('boats.index')
            ->with('success', 'Boat created successfully.');
    }

    public function edit(Boat $boat): Response
    {
        return Inertia::render('Modules/Boat/Pages/Form', [
            'boat' => $boat,
        ]);
    }

    public function update(Request $request, Boat $boat): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive,maintenance'],
        ]);

        $boat->update($validated);

        return redirect()
            ->route('boats.index')
            ->with('success', 'Boat updated successfully.');
    }

    public function destroy(Boat $boat): RedirectResponse
    {
        $boat->delete();

        return redirect()
            ->route('boats.index')
            ->with('success', 'Boat deleted successfully.');
    }
}