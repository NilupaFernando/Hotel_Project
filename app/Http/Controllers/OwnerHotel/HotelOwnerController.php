<?php

namespace App\Http\Controllers\OwnerHotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Jobs\CancelExpiredReservations;
use App\Models\Hotel;
use App\Models\Favorite;
use App\Models\Province;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\BankDetail;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;

use Carbon\Carbon;


class HotelOwnerController extends Controller
{
    public function authUser()
    {
        try {
            $userId = auth()->id();

            $hotel = Hotel::where('hotel_owner_id', $userId)
                ->whereHas('user', function ($query) {
                    $query->where('permit_status', 'active');
                })
                ->get();

            $bookings = Booking::where('hotel_owner_id', $userId)->get();
            $reservations = Reservation::with(['user', 'hotel'])->where('hotel_owner_id', $userId)->get();
            $reservationRooms = ReservationRoom::with('room')->whereIn('reservation_id', $reservations->pluck('id'))->get()->groupBy('reservation_id');
            $bankDetails = BankDetail::where('hotelowner_id', $userId)->exists();

            dispatch(new CancelExpiredReservations());

           return Inertia::render('HotelOwner/Dashboard/Dashboard', [
               'reservations' => $reservations,
               'hotel' => $hotel,
               'reservationRooms' => $reservationRooms,
               'bookings' => $bookings,
               'bankDetails' => $bankDetails,
           ]);
        } catch (\Exception $exception) {
            Log::error('Error fetching reservations', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }


    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:confirmed,cancelled',
            ]);

            $reservation = Reservation::where('id', $id)
                ->where('hotel_owner_id', auth()->id())
                ->firstOrFail();

            $reservation->update(['status' => $validated['status']]);

            DB::commit();
            return back()->with('success', 'Reservation status updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating reservation', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }
}
