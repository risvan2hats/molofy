<?php

namespace App\Modules\Trip\Repositories;

use App\Common\Repositories\BaseRepository;
use App\Modules\Trip\Models\Trip;

class TripRepository extends BaseRepository
{
    protected array $allowedFilters = [
        'status',
        'capacity',
    ];

    protected array $searchable = [
        'name',
        'description',
    ];

    protected array $allowedSorts = [
        'name',
        'capacity',
        'created_at',
    ];

    public function __construct(Trip $trip)
    {
        parent::__construct($trip);
    }
}
