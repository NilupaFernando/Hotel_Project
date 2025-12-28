<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestImage extends Model
{
    use HasFactory;

    protected $table = 'testimage';

    protected $fillable = [
        'name',
        'description'
    ];

    protected $casts = [
        'images' => 'array', // Cast the images field as an array
    ];
}
