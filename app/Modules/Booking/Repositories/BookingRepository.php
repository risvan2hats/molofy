<?php

namespace App\Modules\Booking\Repositories;

use App\Common\Repositories\BaseRepository;
use App\Modules\Booking\Models\Booking;

class BookingRepository extends BaseRepository
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

    public function __construct(Booking $booking)
    {
        parent::__construct($booking);
    }
}
