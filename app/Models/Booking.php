<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'total_price',
        'user_id',
        'hotel_owner_id',
        'status',
        'payment_gateway',
        'payment_status',
        'transaction_id',
        'payment_response',
        'payment_date',
        'expire_at',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'expire_at' => 'datetime',
    ];

    /**
     * Get the reservation that this booking belongs to.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the user who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
    /**
     * Get the hotel owner who owns this booking.
     */
    public function hotelOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hotel_owner_id');
    }
}
