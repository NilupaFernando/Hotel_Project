<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionRoom extends Model
{
    use HasFactory;

    protected $table = 'option_rooms'; // Explicitly specify the table name

    protected $fillable = [
        'hotel_id',
        'room_id',
        'adult_count',
        'child_count',
        'price',
    ];

    // Relationships
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);

    }

    public function optionRoom(){
        return $this->hasMany(OptionRoom::class);
    }
}

