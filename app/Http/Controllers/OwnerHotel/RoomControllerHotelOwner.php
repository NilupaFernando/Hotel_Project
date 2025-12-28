<?php

namespace App\Http\Controllers\OwnerHotel;

use App\Http\Controllers\Controller;

use App\Http\Requests\Room\RoomRequest;
use App\Http\Requests\Room\RoomUpdateRequest;
use App\Models\Favorite;
use App\Models\Hotel;
use App\Models\OptionRoom;
use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoomControllerHotelOwner extends Controller
{
    public function index($id)
    {
        try {
            $hotel = Hotel::select('id','name', 'location')
                ->findOrFail($id);
            $rooms = Room::where('hotel_id', $id)->get();

            return Inertia::render('HotelOwner/Room/Index',[
                'rooms' => $rooms,
                'hotel' => $hotel,
            ]);
        } catch (\Exception $exception) {
            Log::error('Error fetching hotel and rooms', ['error' => $exception->getMessage()]);
            return redirect()->route('hotelOwner.hotel.index')->with('error', 'Failed to load hotel details.');
        }
    }

    public function create($id)
    {
        try {
            $hotel = Hotel::select('id','name', 'location', 'contact_number', 'email', 'room_types')
                ->findOrFail($id);
            return Inertia::render('HotelOwner/Room/Create', [
                'hotel'=>$hotel
            ]);

        } catch (\Exception $exception) {
            Log::info('Hotel created successfully', [$exception]);
            return redirect()->route('hotelOwner.hotel.show')->with('error', 'Failed to load hotel details.');
        }
    }

    public function store(RoomRequest $request)
    {
        try {
            $validatedData = $request->validated();
//            dd($validatedData);
            $hotel = Hotel::findOrFail($validatedData['hotel_id']);

            if ($hotel) {
                $room = Room::create([
                    'hotel_id'       => $request->input('hotel_id'),
                    'type'      => $request->input('type'),
                    'bookable_rooms' => $request->input('available_rooms'),
                    'available_rooms'=> $request->input('available_rooms'),
                    'features'       => is_array($request->input('features')) ? implode(',', $request->input('features')) : $request->input('features'),
                    'free_services'  => is_array($request->input('free_services')) ? implode(',', $request->input('free_services')) : $request->input('free_services'),
                    'price_per_adult'=> $request->input('price_per_adult',0),
                    'discount'       => $request->input('discount', 0),
                    'description'    => $request->input('description'),
                    ]);
                if ($room && isset($validatedData['guestOptions'])) {
                    foreach ($validatedData['guestOptions'] as $index => $option) {
                        OptionRoom::create([
                            'hotel_id'     => $validatedData['hotel_id'],
                            'room_id'      => $room->id,
                            'adult_count'  => $option['max_adult'] ?? 0,
                            'child_count'  => $option['max_children'] ?? 0,
                            'price'        => (float) ($option['price_per_night'] ?? 0.0),
                        ]);
                    }
                }
                return redirect()->route('hotelOwner.hotel.show', ['id' => $hotel->id])
                    ->with('success', 'Room created successfully');
            }
        } catch (\Exception $exception) {
//            dd($exception->getMessage());
            Log::error('Error storing hotel', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return redirect()->route('hotelOwner.hotel.show',['id' => $request->input('hotel_id')])->with('error', 'Failed to load hotel details.');
        }
    }

    public function edit($id)
    {
        try {
            $room = Room::with(['hotel:id,name,location,contact_number,email,room_types','optionRoom'])->findOrFail($id);
//            dd($room);
            $room->features = explode(',', $room->features);
            $room->free_services = explode(',', $room->free_services);

            return Inertia::render('HotelOwner/Room/edit', [
                'room'=>$room
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

    public function update(RoomUpdateRequest $request, $id)
    {
        try {
//            dd($request->all());
            $room = Room::findOrFail($id);

            $validatedData = $request->validated();
            $validatedData['features'] = is_array($request->features) ? implode(',', $request->features) : $request->features;
            $validatedData['free_services'] = is_array($request->free_services) ? implode(',', $request->free_services) : $request->free_services;
            $validatedData['bookable_rooms'] = $validatedData['available_rooms']; // Ensure bookable_rooms is updated

            $room->update([
                'hotel_id'       => $request->input('hotel_id'),
                'type'           => $request->input('type'),
                'bookable_rooms' => $request->input('available_rooms'),
                'available_rooms'=> $request->input('available_rooms'),
                'features'       => $validatedData['features'],
                'free_services'  => $validatedData['free_services'],
                'price_per_adult'=> $request->input('price_per_adult',0),
                'discount'       => $request->input('discount', 0),
                'description'    => $request->input('description'),
            ]);

            $existingOptions = OptionRoom::where('room_id', $room->id)->get()->keyBy('id');

            if (isset($validatedData['guestOptions'])) {
                $updatedOptionIds = [];

                foreach ($validatedData['guestOptions'] as $option) {
                    $optionRoom = OptionRoom::where([
                        'hotel_id' => $validatedData['hotel_id'],
                        'room_id' => $room->id,
                        'adult_count' => $option['max_adult'] ?? 0,
                        'child_count' => $option['max_children'] ?? 0,
                    ])->first();

                    if ($optionRoom) {
                        $optionRoom->update([
                            'price' => (float) ($option['price_per_night'] ?? 0.0),
                        ]);

                        $updatedOptionIds[] = $optionRoom->id;
                    } else {
                        $newOption = OptionRoom::create([
                            'hotel_id' => $validatedData['hotel_id'],
                            'room_id' => $room->id,
                            'adult_count' => $option['max_adult'] ?? 0,
                            'child_count' => $option['max_children'] ?? 0,
                            'price' => (float) ($option['price_per_night'] ?? 0.0),
                        ]);

                        $updatedOptionIds[] = $newOption->id;
                    }
                }

                if ($request->has('removeRoomOption')) {
                    $removedIds = $request->input('removeRoomOption'); // Already an array
                    if (!empty($removedIds)) {
                        OptionRoom::whereIn('id', $removedIds)->delete();
                    }
                }
            }

            // Delete options that were not updated (to remove stale data)
            return redirect()->route('hotelOwner.hotel.show', ['id' => $room->hotel_id])
                ->with('success', 'Room Updated successfully');
        } catch (\Exception $exception) {
            Log::error('Error updating hotel', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $room = Room::findOrFail($id);
            $room->delete();
        } catch (\Exception $exception) {
            Log::error('Error deleting hotel', [
                'hotel_id' => $id,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);

            return redirect()->route('hotelOwner.hotel.index')->with('error', 'Failed to Delete hotel.');
        }
    }
}
