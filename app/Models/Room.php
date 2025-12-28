<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'id',
        'hotel_id',
        'type',
        'bookable_rooms',
        'booked_rooms',
        'available_rooms',
        'max_adult',
        'max_children',
        'price_per_adult',
        'price_per_child',
        'features',
        'free_services',
        'discount',
        'description',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }


    public function optionRoom()
    {
        return $this->hasMany(OptionRoom::class, 'room_id', 'id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }


}

