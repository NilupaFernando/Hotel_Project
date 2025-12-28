<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_owner_id',
        'district_id',
        'province_name',
        'accommodation_type',
        'name',
        'description',
        'category',
        'location',
        'latitude',
        'longitude',
        'thumbnail_image',
        'images',
        'star',
        'price_per_night',
        'amenities',
        'room_types',
        'check_in_time',
        'check_out_time',
        'contact_number',
        'email',
        'website',
    ];

    protected $casts = [
        'images' => 'array',
        'room_types' => 'array',
    ];

    // Relationships
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'hotel_owner_id'); // Updated from 'user_id'
    }

    public function optionRoom(){
        return $this->hasMany(OptionRoom::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function bankDetails()
    {
        return $this->hasMany(BankDetail::class, 'hotel_id');
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class);
    }
}
?>
