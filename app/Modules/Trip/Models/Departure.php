<?php

namespace App\Modules\Trip\Models;

use App\Modules\Boat\Models\Boat;
use App\Modules\Booking\Models\Booking;
use App\Modules\Operator\Models\Operator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Departure extends Model
{
    protected $fillable = [
        'trip_id',
        'boat_id',
        'departure_at',
        'arrival_at',
        'meeting_point',
        'meeting_instructions',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
        ];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function boat(): BelongsTo
    {
        return $this->belongsTo(Boat::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(Operator::class)
            ->withTimestamps();
    }
}
