<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Reservation;
use Carbon\Carbon;


class UserBookingController extends Controller
{
    public function store(StoreBookingRequest $request)
    {
        try {
//            dd($request->all());

            $validatedData = $request->validated();

            $reservation = Reservation::findOrFail($validatedData['reservation_id']);

//            dd($reservation);

            $payment = new Booking();

            $payment->reservation_id = $reservation->id;
            $payment->total_price = $reservation->total_price;
            $payment->user_id = $reservation->user_id;
            $payment->hotel_owner_id = $reservation->hotel_owner_id;
            $payment->payment_gateway = $validatedData['payment_gateway'];

            $payment->transaction_id = $validatedData['transaction_id'] ?? null;
            $payment->payment_response = $validatedData['payment_response'] ?? null;
            $payment->payment_date = $validatedData['payment_date'] ?? Carbon::now();
            $payment->expire_at = $reservation->expire_at;

            // Save the model
            $payment->save();

            return response()->json(['message' => 'Payment saved successfully', 'data' => $payment], 201);

        } catch (\Exception $e) {
            \Log::error("Error saving booking: " . $e->getMessage());
            return response()->json(['message' => 'Error saving booking',$e->getMessage()], 500);
        }
    }
}
