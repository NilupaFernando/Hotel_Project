<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'hotel_id',
        'hotel_owner_id',
        'reservation_type',
        'check_in_date',
        'check_out_date',
        'expire_at',
        'is_completed',
        'total_price',
        'status',
        'rejection_reason',
        'deleted_by_user',
        'deleted_by_owner',
        'special_requests',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'deleted_by_user' => 'boolean',
        'deleted_by_owner' => 'boolean',
    ];

    // Append ISO formatted expire time for frontend JSON
    protected $appends = ['expire_at_iso'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function ReservationRoom()
    {
        return $this->belongsTo(ReservationRoom::class);
    }

//    protected static function boot()
//    {
//        parent::boot();
//        static::creating(function ($reservation) {
//            $reservation->expire_at = Carbon::now()->addDays(7); // Set expiration to 7 days after creation
//        });
//    }

    public function scopeExpired($query)
    {
        return $query->where('expire_at', '<', now())->where('status', '!=', 'cancelled');
    }

    public function isExpired()
    {
        return $this->expire_at && Carbon::parse($this->expire_at)->isPast();
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    // ISO 8601 formatted expire_at for reliable JS parsing (includes offset)
    public function getExpireAtIsoAttribute()
    {
        if (!$this->expire_at) return null;
        try {
            return Carbon::parse($this->expire_at)->toIso8601String();
        } catch (\Exception $e) {
            return null;
        }
    }
}
