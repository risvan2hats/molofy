<?php

namespace App\Modules\Boat\Repositories;

use App\Common\Repositories\BaseRepository;
use App\Modules\Boat\Models\Boat;

class BoatRepository extends BaseRepository
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

    public function __construct(Boat $boat)
    {
        parent::__construct($boat);
    }
}
