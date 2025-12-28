<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Favorite;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HotelController extends Controller
{
    public function show()

    {
        try {
            $userId = Auth::id();
            $favorites = Favorite::where('user_id', $userId)->get();
            $hotels = Hotel::all();
//            dd($hotels);

            $districts = District::with('province')->get();

            // $hotels->images = json_decode($hotels->images, true);

            return Inertia::render('User/ViewHotel/ViewHotel',[
                'hotels' => $hotels,
                'districts' => $districts,
                'favorites' => $favorites,
            ]);
//            return Inertia::render('Admin/Hotel/Show');
        } catch (\Exception $exception) {
            Log::error('Error updating hotel', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $favorites = Favorite::where('user_id', $userId)->get();
            $hotels = Hotel::all();
            $districts = District::with('province')->get();

            return Inertia::render('User/Hotel/AllHotel', [
                'hotels' => $hotels,
                'districts' => $districts,
                'favorites' => $favorites,
                'isOffer' => $request->boolean('isOffer')
            ]);
        } catch (\Exception $exception) {
            Log::error('Error fetching hotels', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }
}
