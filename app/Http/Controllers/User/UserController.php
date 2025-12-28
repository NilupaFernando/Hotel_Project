<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Reservation;
use App\Models\Hotel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function userDashboard()
    {
        $districts = District::all();
        $topHotels = Hotel::withCount('reservations')
            ->with('user')
            ->whereHas('user', function ($query) {
                $query->where('permit_status', 'active');
            })
            ->orderBy('reservations_count', 'desc')
            ->take(5)
            ->get();
            
        return Inertia::render('User/Dashboard/Dashboard',[
            'districts'=>$districts,
            'topHotels'=>$topHotels
        ]);
    }
}
