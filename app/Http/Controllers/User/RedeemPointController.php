<?php

namespace App\Http\Controllers\User;


use App\Models\OfferPoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RedeemPointController extends Controller
{
    public function redeem(Request $request)
    {
        try {
            $user = Auth::user();
            $pointsToRedeem = (int) $request->input('points');

            if ($pointsToRedeem <= 0) {
                return redirect()->back()->with('error', 'Invalid points value.');
            }

            $offerPoint = OfferPoint::where('user_id', $user->id)->first();

            if (!$offerPoint || $offerPoint->points < $pointsToRedeem) {
                return redirect()->back()->with('error', 'Not enough points to redeem.');
            }

            $offerPoint->decrement('points', $pointsToRedeem);
            $offerPoint->increment('total_redeemed', $pointsToRedeem);

            return redirect()->back()->with('success', 'Points redeemed successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }

    public function getRedeem()
    {
        try {
            $user = Auth::user();
            $offerPoint = OfferPoint::where('user_id', $user->id)->first();

            return view('offer_points.redeem', compact('offerPoint'));

        } catch (\Exception $e) {
            Log::error('Error loading redeem form: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while loading the redeem page.');
        }
    }
}
