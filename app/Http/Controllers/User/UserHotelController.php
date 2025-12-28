<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Hotel;
use App\Models\Favorite;
use App\Models\Province;
use App\Models\User;
use App\Models\OfferPoint;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserHotelController extends Controller
{
    public function index(Request $request)
    {
        try {
            $hotels = Hotel::whereHas('user', function ($query) {
                $query->where('permit_status', 'active');
            })
                ->latest()
                ->get();
            $userId = Auth::id();
            $users = User::find($userId);
            $provinces = Province::all();
            $favorites = Favorite::where('user_id', $userId)->get();

            // dd($province,$hotels,$favourite,"user all hotel show part. province, hotels, favourite");

            return Inertia::render('User/Hotel/AllHotels', [
                'users' => $users,
                'provinces' => $provinces,
                'hotels' => $hotels,
                'favorites' => $favorites,
                'isOffer' => $request->boolean('isOffer')
            ]);
        } catch (\Exception $exception) {
            Log::error('Error fetching hotels', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong while fetching hotels.');
        }
    }

    public function show($id, Request $request)
    {
        try {
            $userId = Auth::id();
            $isOffer = $request->boolean('isOffer');
            $offerPoints = OfferPoint::where('user_id', auth()->id())->sum('points');

            $users = User::find($userId);
            $favorites = Favorite::where('user_id', $userId)->get();
            $hotel = Hotel::with(['district', 'rooms', 'optionRoom'])
                ->whereHas('user', function ($query) {
                    $query->where('permit_status', 'active');
                })
                ->where('id', $id)
                ->firstOrFail();

            $hotel->images = json_decode($hotel->images ?? '[]', true);

            // attach average rating and current user's rating (if any)
            $avg = Rating::where('hotel_id', $hotel->id)->avg('rating');
            $hotel->avg_rating = $avg ? round($avg, 1) : 0;
            $hotel->user_rating = null;
            if ($userId) {
                $hotel->user_rating = Rating::where('hotel_id', $hotel->id)->where('user_id', $userId)->value('rating');
                if ($hotel->user_rating !== null) {
                    $hotel->user_rating = (int)$hotel->user_rating;
                }
            }

           return Inertia::render('User/ViewHotel/ViewHotel',[
                'users' => $users,
                'hotels' => $hotel,
                'favorites' => $favorites,
                'isOffer' => $isOffer,
                'offerPoints' => $offerPoints
           ]);
        } catch (\Exception $exception) {
            Log::error('Error updating hotel', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function findByState($name)
    {
        try {
            $userId = Auth::id();
            $users = User::find($userId);
            $favorites = Favorite::where('user_id', $userId)->get();
            $provinces = Province::all();

            // Normalize the incoming name to improve matching (case-insensitive, ignore spaces and trailing plurals)
            $normalized = strtolower($name);
            $normalized = preg_replace('/[^a-z0-9]/', '', $normalized); // remove non-alphanum
            $normalized = rtrim($normalized, 's'); // remove trailing 's' (e.g., 'Forests' -> 'forest')

            // Normalize stored category by removing spaces and lowercasing, then match using LIKE
            $hotels = Hotel::whereRaw("LOWER(REPLACE(category, ' ', '')) LIKE ?", ['%'.$normalized.'%'])->get();

            return Inertia::render('User/Hotel/AllHotels', [
                'users' => $users,
                'provinces' => $provinces,
                'hotels' => $hotels,
                'favorites' => $favorites,
                'isOffer' => false,
            ]);

        } catch (\Exception $exception) {
            Log::error('Error fetching hotels', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return response()->json(['error' => 'Something went wrong while fetching hotels.'], 500);
        }
    }

    /**
     * Store or update a user's rating for a hotel and return the new average.
     */
    public function rating(Request $request, $id): JsonResponse
    {
        $request->merge(['hotel_id' => $id]);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            // create or update user's rating
            Rating::updateOrCreate([
                'user_id' => $userId,
                'hotel_id' => $id,
            ], [
                'rating' => $validated['rating'],
            ]);

            // compute new average (do NOT overwrite hotel's owner-set star)
            $avg = Rating::where('hotel_id', $id)->avg('rating');

            return response()->json([
                'message' => 'Rating saved',
                'avg_rating' => $avg,
                'rating' => $avg,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error saving rating', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to save rating'], 500);
        }
    }
}
