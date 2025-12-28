<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    protected $table = 'bank_detail';

    protected $fillable = [
        'hotel_id',
        'hotelowner_id',
        'bank_name',
        'acc_nomber',
        'branch',
        'acc_holder_name',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'hotelowner_id');
    }
}
