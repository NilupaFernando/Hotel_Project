<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FavouriteHotelsController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $favorites = Favorite::with('hotel.district')
                ->where('user_id', $userId)
                ->latest()
                ->get();

            return Inertia::render('User/Favourite/FavouriteHotels', [
                'favorites' => $favorites,
            ]);
        }catch (\Exception $exception) {
            Log::error('Error fetching favorites for user ' . $userId . ': ' . $exception->getMessage(), [
                'user_id' => $userId,
                'exception' => $exception
            ]);
            return back()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $userId = Auth::id();
            $save = Favorite::create([
                'user_id' => $userId,
                'hotel_id' => $request->input('id'),
            ]);

        } catch (\Exception $exception) {
            Log::error('Error in storing hotel', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);

            return back()->with('error', 'Something went wrong!');
        }
    }


    public function delete($id)
    {
        try {
            $favorite = Favorite::where('hotel_id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $favorite->delete();

        } catch (\Exception $exception) {
            Log::error('Error deleting hotel', [
                'hotel_id' => $id,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function deleteMultiple(Request $request)
{
    $favoriteIds = $request->input('favorite_ids');

    if (empty($favoriteIds) || !is_array($favoriteIds)) {
        return response()->json(['message' => 'No favorites selected for deletion.'], 400);
    }

    try {
        // Assuming you have a Favorite model
        Favorite::whereIn('id', $favoriteIds)->delete();

        return response()->json(['message' => 'Favorites deleted successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred while deleting favorites.'], 500);
    }
}
    
}
