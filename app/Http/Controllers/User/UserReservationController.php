<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Jobs\CancelExpiredReservations;
use App\Models\Hotel;
use App\Models\OfferPoint;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Inertia\Inertia;

class UserReservationController extends Controller
{

    public function index()
    {
        try {
            $user = Auth::user();
            $offerPoints = OfferPoint::where('user_id', auth()->id())->sum('points');

            $reservations = Reservation::with('hotel','ReservationRoom')->where('user_id', auth()->id())->get();
            $reservationRooms = [];
            $Bookings = Booking::with(['hotelOwner', 'hotelOwner.hotels','reservation'])->where('user_id', auth()->id())->get();

            foreach ($reservations as $reservation) {
                $reservationRooms[$reservation->id] = ReservationRoom::where('reservation_id', $reservation->id)->with('room')->get();
            }

            dispatch(new CancelExpiredReservations());

            return Inertia::render('User/Reservations/Reservations', [
                'offerPoints' => $offerPoints,
                'reservations' => $reservations,
                'reservationRooms' => $reservationRooms,
                'bookings' => $Bookings,
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

    public function store(StoreReservationRequest $request)
    {
        DB::beginTransaction();
        try {

            $validated = $request->validated();
        //    dd($validated['offer']);

        // dd($request->all());

            $hotel_owner_id = Hotel::where('id', $validated['hotel_id'])->value('hotel_owner_id');

            $currentDate = Carbon::now();
            $checkInDate = Carbon::parse($validated['check_in_date']);

            $daysUntilCheckIn = $currentDate->diffInDays($checkInDate, false);
            $hoursUntilCheckIn = $currentDate->diffInHours($checkInDate);

            $waitingTime = 48; // default safe value
            $reservationType = "standard"; // default safe value

            if ($validated['offer']) {
                if ($daysUntilCheckIn > 7) {
                    $waitingTime = 72; // 3 days
                    $reservationType = "offer";
                } elseif ($daysUntilCheckIn >= 3) {
                    $waitingTime = 48; // 2 days
                    $reservationType = "offer";
                } elseif ($hoursUntilCheckIn < 24 && $hoursUntilCheckIn > 0) {
                    $waitingTime = 5; // 5 hour
                    $reservationType = "offer";
                }
            }else{
               if ($daysUntilCheckIn > 7) {

                    // FLEXIBLE
                    $waitingTime = 72; // 3 days
                   $reservationType = 'flexible';

                } elseif ($daysUntilCheckIn >= 3) {

                 // STANDARD
                 $waitingTime = 48; // 2 days
                $reservationType = 'standard';

                 } elseif ($hoursUntilCheckIn > 0 && $hoursUntilCheckIn < 24) {

               // URGENT
                 $waitingTime = 5; // 5 hours
                $reservationType = 'urgent';

              } else {

            // Fallback safety (same day / past edge cases)
             $waitingTime = 5;
            $reservationType = 'urgent';
            }
            }
            // extra safety
           if (empty($reservationType)) {
          $reservationType = 'standard';
         }


            $expireAt = Carbon::now()->addHours($waitingTime);

            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'hotel_owner_id' => $hotel_owner_id,
                'hotel_id' => $validated['hotel_id'],
                'reservation_type' => $reservationType,
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'total_price' => $validated['total_price'],
                'expire_at' => $expireAt,
            ]);

            if (isset($validated['room_id'])) {
                foreach ($validated['room_id'] as $index => $room_id) {
                    if (is_null($room_id)) {
                        continue;
                    }
                    ReservationRoom::create([
                        'reservation_id' => $reservation->id,
                        'room_id'        => $room_id,
                        'room_count'     => $validated['room_count'][$index] ?? 1,
                        'adult_count'    => $validated['adult_count'][$index] ?? 0,
                        'child_count'    => $validated['child_count'][$index] ?? 0,
                        'day_count'      => $validated['day_count'][$index] ?? 1,
                        'price'          => $validated['price'][$index] ?? 0,
                    ]);
                }
            }
            DB::commit();
            
return back()->with('success', 'Reservation created successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Reservation Store Error', [
                'user_id' => auth()->id(),
                'error_message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function cancel($id)
    {
        try {
            $reservation = Reservation::where('user_id', auth()->id())->findOrFail($id);
            $reservation->update([
                'status' => 'cancelled',
                'rejection_reason' => 'Rejected by user'
            ]);

            return back()->with('success', 'Reservation cancelled successfully.');
        } catch (\Exception $exception) {
            Log::error('Error cancelling reservation', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $reservation = Reservation::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Mark as deleted by user
            $reservation->update(['deleted_by_user' => true]);

            // If both user and owner have deleted, remove permanently
            if ($reservation->deleted_by_owner) {
                ReservationRoom::where('reservation_id', $id)->delete();
                $reservation->delete();
            }

            DB::commit();
            return back()->with('success', 'Reservation deleted from your view successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error deleting reservation', [
                'reservation_id' => $id,
                'user_id' => auth()->id(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }


    public function pay($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
//            dd($reservation->user_id);

            $merchant_id = env('PAYHERE_MERCHANT_ID');
            $secret = env('PAYHERE_SECRET');

            $paymentData = [
                'merchant_id' => env('PAYHERE_MERCHANT_ID'),
                'return_url' => env('PAYHERE_REDIRECT_URL'),
                'cancel_url' => env('PAYHERE_CANCEL_URL'),
                'notify_url' => env('PAYHERE_NOTIFY_URL'),
                'order_id' => uniqid(),
                'currency' => 'LKR',
                'amount' =>  $reservation->total_price,
            ];

            $hash = strtoupper(
                md5(
                    $merchant_id .
                    $paymentData['order_id'] .
                    number_format($paymentData['amount'], 2, '.', '') .
                    $paymentData['currency'] .
                    strtoupper(md5($secret))
                )
            );

            $paymentData['hash'] = $hash;

            return response()->json([
                'payment_url' => 'https://sandbox.payhere.lk/pay/checkout',
                'payment_data' => $paymentData,
                'success' => true,
                'reservation' => $reservation,
            ]);

        } catch (\Exception $exception) {
            Log::error('Error fetching reservation for payment', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!'
            ], 500);
        }
    }

}
