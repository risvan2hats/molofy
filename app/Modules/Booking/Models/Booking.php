<?php

namespace App\Modules\Booking\Models;

use App\Modules\Trip\Models\Departure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'departure_id',
        'reference',
        'confirmation_token',
        'adults',
        'children',
        'infants',
        'total_seats',
        'total_amount',
        'deposit_amount',
        'remaining_amount',
        'status',
        'payment_status',
        'cancelled_at',
        'cancellation_refund_amount',
        'refund_status',
        'refund_processed_at',
        'refund_processed_by',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'cancelled_at' => 'datetime',
            'cancellation_refund_amount' => 'decimal:2',
            'reminder_sent_at' => 'datetime',
            'refund_processed_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(BookingGuest::class);
    }
}
