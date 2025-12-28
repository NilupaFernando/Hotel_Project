<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Hotel;
use App\Models\Province;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class ExploreHotelController extends Controller
{
    public function search($name)
    {
        try {
            if (empty($name)) {
                throw new \Exception('Search parameter is missing.');
            }

            $categories = explode(',', $name);

            $hotels = Hotel::where(function ($query) use ($categories) {
                foreach ($categories as $category) {
                    $query->orWhere('category', 'LIKE', "%$category%");
                }
            })->whereHas('user', function ($query) {
                    $query->where('permit_status', 'active');
                })->with('district')->latest()->get();

            $province = Province::all();

            $userId = Auth::id();
            $favourite = Favorite::where('user_id',$userId)->get();

            dd($hotels,"paginate eka ayin karala aththe");


            return inertia('ExploreHotel', [
                'hotels' => $hotels,
                'province' => $province,
                'favourite'=>$favourite
            ]);
        } catch (\Exception $e) {
            \Log::error('Hotel search error: ' . $e->getMessage());

            return inertia('/', [
                'hotels' => [],
                'error' => 'Something went wrong while searching for hotels.',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function index()
    {
        try {
            $hotels = Hotel::with(['district', 'rooms'])
                ->whereHas('user', function ($query) {
                    $query->where('permit_status', 'active');
                })
                ->latest()
                ->get();

            $userId = Auth::id();
            $favorites = Favorite::where('user_id', $userId)->get();
            $provinces = Province::all();
            return Inertia::render('ExploreHotel', [
               'hotels' => $hotels,
               'favorites' => $favorites,
                'provinces' => $provinces
           ]);
        } catch (\Exception $exception) {
            Log::info('Hotel created successfully', [$exception]);
            return inertia('/', [
                'hotels' => [],
                'error' => 'Something went wrong while searching for hotels.',
                'message' => $exception->getMessage(),
            ]);
        }
    }

}
