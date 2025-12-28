<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'total_earned',
        'total_redeemed',
    ];

    // Relationship: OfferPoint belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
