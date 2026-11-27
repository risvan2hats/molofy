<?php

namespace App\Modules\Booking\Models;

use App\Modules\Operator\Models\Operator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingGuest extends Model
{
    protected $fillable = [
        'booking_id',
        'guest_type',
        'guest_number',
        'boarded_at',
        'checked_in_by',
    ];

    protected function casts(): array
    {
        return [
            'boarded_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(
            Operator::class,
            'checked_in_by'
        );
    }
}
