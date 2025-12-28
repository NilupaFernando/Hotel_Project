<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';

    protected $fillable = [
        'province_id',
        'name',
        'about',
        'mostly_popular',
        'location',
        'image',
        'travel_season',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function hotels()
    {   
        return $this->hasMany(Hotel::class);
    }
}









