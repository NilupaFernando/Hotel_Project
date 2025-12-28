<?php

namespace App\Http\Controllers;

use App\Jobs\CancelExpiredReservations;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationExpiryController extends Controller
{
    /**
     * Process expired reservations and refresh Inertia page
     */
    public function process(Request $request)
    {
        try {
            // Run expiry job immediately
            $job = new CancelExpiredReservations();
            $job->handle();

            // IMPORTANT: Return Inertia-friendly response
            return redirect()->back();

            // OR (if redirect back fails in some cases)
            // return Inertia::location(route('hotel-owner.dashboard'));

        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}
