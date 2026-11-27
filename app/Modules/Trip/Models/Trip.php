<?php

namespace App\Modules\Trip\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'duration_minutes',
        'price_adult',
        'price_child',
        'price_infant',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'price_adult' => 'decimal:2',
            'price_child' => 'decimal:2',
            'price_infant' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }
}