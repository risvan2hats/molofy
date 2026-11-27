<?php

namespace App\Modules\Boat\Controllers;

use App\Common\Controllers\BaseController;
use App\Modules\Boat\Repositories\BoatRepository;
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
}