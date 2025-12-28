<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;


class WelcomeController extends Controller
{
    public function index()
    {

        //need to create best hotel part

        $districts = District::all();
        $userId = Auth::id();
        $user = User::find($userId);
        $topHotels = Hotel::withCount('reservations')
        ->with('user')
        ->whereHas('user', function ($query) {
            $query->where('permit_status', 'active');
        })
            ->orderBy('reservations_count', 'desc')
            ->take(5)
            ->get();
        return Inertia::render('Welcome', [
            'districts'=>$districts,
            'user'=>$user,
            'topHotels'=>$topHotels
        ]);
    }
}



