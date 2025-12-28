<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_id',
    ];

    /**
     * Relationship with User (each favorite belongs to a user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Hotel (each favorite belongs to a hotel)
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
