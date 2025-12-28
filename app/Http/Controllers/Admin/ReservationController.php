<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HotelOwnerInvitation\UpdateHotelOwnerRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ReservationController extends Controller
{


    public function store(StoreReservationRequest $request)
    {
        DB::beginTransaction();
        try {
//            dd($request->all());
            $validated = $request->validated();

            $hotel_owner_id = Hotel::where('id', $validated['hotel_id'])->value('hotel_owner_id');

            $currentDate = Carbon::now();
            $checkInDate = Carbon::parse($validated['check_in_date']);

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

            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'hotel_owner_id' => $hotel_owner_id,
                'hotel_id' => $validated['hotel_id'],
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

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Reservation Store Error', [
                'user_id' => auth()->id(),
                'error_message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            return response()->json([
                'success' => $exception,
                'message' => 'An error occurred while creating the reservation. Please try again later.'
            ], 500);
        }

    }

    public function update(UpdateHotelOwnerRequest $request, string $id)
    {

    }
}
