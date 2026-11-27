<?php

namespace App\Modules\Operator\Models;

use App\Modules\Booking\Models\BookingGuest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Modules\Trip\Models\Departure;

class Operator extends Model
{
    protected $fillable = [
        'name',
        'email',
        'pin_hash',
        'is_active',
    ];

    protected $hidden = [
        'pin_hash',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(BookingGuest::class,'checked_in_by');
    }

    public function departures(): BelongsToMany
    {
        return $this->belongsToMany(Departure::class)
            ->withTimestamps();
    }
}
