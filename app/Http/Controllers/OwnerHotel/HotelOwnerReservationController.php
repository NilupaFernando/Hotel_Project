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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;

use Carbon\Carbon;

class HotelOwnerReservationController extends Controller
{

    public function index()
    {
        try {
            $reservations = Reservation::where('hotel_owner_id', auth()->id())->get();
            $reservationRooms = ReservationRoom::whereIn('reservation_id', $reservations->pluck('id'))->get()->groupBy('reservation_id');

            dispatch(new CancelExpiredReservations());

           return Inertia::render('HotelOwner/ReservationRequests/ReservationRequests', [
               'reservations' => $reservations,
               'reservationRooms' => $reservationRooms,
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

    /**
     * Update the status of a reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     *         Expects a JSON request with a 'status' parameter.
     *         The 'status' parameter must be a string with a value of 'confirmed' or 'cancelled'.
     * @param  int  $id Reservation ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'status' => 'nullable|string|in:confirmed,cancelled',
                'is_completed' => 'nullable|boolean',
                'rejection_reason' => 'nullable|string',
            ]);

            $reservation = Reservation::where('id', $id)
                ->where('hotel_owner_id', auth()->id())
                ->firstOrFail();

            $currentDate = Carbon::now();
            $checkInDate = Carbon::parse($reservation->check_in_date);

            $daysUntilCheckIn = $currentDate->diffInDays($checkInDate, false);
            $hoursUntilCheckIn = $currentDate->diffInHours($checkInDate);

            $waitingTime = 24;

            if ($daysUntilCheckIn > 7) {
                $waitingTime = 72; // 3 days
            } elseif ($daysUntilCheckIn >= 3) {
                $waitingTime = 48; // 2 days
            } elseif ($hoursUntilCheckIn < 24 && $hoursUntilCheckIn > 0) {
                $waitingTime = 5; // 5 hour
            }

            $expireAt = Carbon::now()->addHours($waitingTime);

            // If owner marked reservation as completed, set the flag
            if (isset($validated['is_completed']) && $validated['is_completed']) {
                $reservation->update([
                    'is_completed' => true,
                ]);
            } else {
                // Set rejection reason if status is being changed to cancelled
                $rejectionReason = null;
                if (isset($validated['status']) && $validated['status'] === 'cancelled') {
                    $rejectionReason = $validated['rejection_reason'] ?? 'Rejected by hotel owner';
                }

                $updateData = [
                    'status' => $validated['status'] ?? $reservation->status,
                    'expire_at' => $expireAt,
                ];

                if ($rejectionReason) {
                    $updateData['rejection_reason'] = $rejectionReason;
                }

                $reservation->update($updateData);
            }

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

    /**
     * Permanently delete a reservation.
     *
     * @param  int  $id Reservation ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $reservation = Reservation::where('id', $id)
                ->where('hotel_owner_id', auth()->id())
                ->firstOrFail();

            // Mark as deleted by owner
            $reservation->update(['deleted_by_owner' => true]);

            // If both user and owner have deleted, remove permanently
            if ($reservation->deleted_by_user) {
                ReservationRoom::where('reservation_id', $id)->delete();
                $reservation->delete();
            }

            DB::commit();
            return back()->with('success', 'Reservation deleted for owner view successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error deleting reservation', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
            return back()->with('error', 'Failed to delete reservation!');
        }
    }

}
