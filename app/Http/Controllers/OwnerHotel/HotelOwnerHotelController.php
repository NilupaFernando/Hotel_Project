<?php

namespace App\Http\Controllers\OwnerHotel;

use App\Http\Controllers\Controller;

use App\Http\Requests\Hotel\StoreHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Models\District;
use App\Models\Favorite;
use App\Models\BankDetail;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class HotelOwnerHotelController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $hotels = Hotel::with('district', 'rooms')
                ->where('hotel_owner_id', $userId)
                ->latest()->get();

            dd($hotels,"paginate eka ayin karala aththe");
//            return Inertia::render('HotelOwner/Hotel/Index',[
//                'hotels' => $hotels,
//            ]);
        } catch (\Exception $exception) {
            Log::info('Hotel created successfully', [$exception]);
        }
    }

    public function create()
    {
        try {
            $districts = District::with('province')->get();
            return Inertia::render('HotelOwner/Hotel/Create',[
                'districts' => $districts,
            ]);

        } catch (\Exception $exception) {
            Log::info('Hotel created successfully', [$exception]);
            return Inertia::render('HotelOwner/Hotel/Index');
        }
    }

    public function store(StoreHotelRequest $request)
    {
        try {
            Log::info('Hotel store request received', $request->all());

            $validatedData = $request->validated();

            $validatedData['category'] = is_array($request->category) ? implode(',', $request->category) : $request->category;
            $validatedData['amenities'] = is_array($request->amenities) ? implode(',', $request->amenities) : $request->amenities;
            $validatedData['room_types'] = is_array($request->room_types) ? implode(',', $request->room_types) : $request->room_types;

            $imagePaths = [];

            // Handle new images
            if ($request->hasFile('photo')) {
                foreach ($request->file('photo') as $image) {
                    if ($image instanceof \Illuminate\Http\UploadedFile) {
                        $newImagePath = $image->store('images', 'public');
                        $imagePaths[] = $newImagePath;
                    }
                }
            }

            // Handle removed
            if ($request->has('removed_images')) {
                $removedImages = json_decode($request->removed_images, true);
                if (is_array($removedImages)) {
                    foreach ($removedImages as $removedImage) {
                        if (\Storage::disk('public')->exists($removedImage)) {
                            \Storage::disk('public')->delete($removedImage);
                        }
                    }
                }
            }

            //main thmbnail part
            $imagePath = null;
            if ($request->hasFile('thumbnail_image')) {
                $image = $request->file('thumbnail_image');
                $imageName = time() . '_' . $image->getClientOriginalName(); // Unique name for the file
                $imagePath = $image->storeAs('uploads/hotels', $imageName, 'public'); // Save in the 'public/uploads/images' directory
            }

            $validatedData['thumbnail_image'] = $imagePath;

            $validatedData['images'] = json_encode($imagePaths);

            $validatedData['hotel_owner_id'] = auth()->id();

            $hotel = Hotel::create($validatedData);

            Log::info('Hotel created successfully', ['hotel_id' => $hotel->id]);
            return redirect()->route('hotel.index')->with('success', 'Hotel created successfully');

        } catch (\Exception $exception) {
            Log::error('Error storing hotel', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function edit($id)
    {
        try {
            $hotel = Hotel::with('district','rooms')->findOrFail($id);
            $districts = District::with('province')->get();

            $hotel->images = json_decode($hotel->images, true);

            $hotel->category = explode(',', $hotel->category);
            $hotel->amenities = explode(',', $hotel->amenities);
            $hotel->room_types = explode(',', $hotel->room_types);

            return Inertia::render('HotelOwner/Hotel/edit',[
                'hotel' => $hotel,
                'districts' => $districts,
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

    public function update(UpdateHotelRequest $request, $id)
    {
        try {

//            dd($request->all());
            Log::info('Hotel update request received', $request->all());

            $hotel = Hotel::findOrFail($id);
            $validatedData = $request->validated();

            $validatedData['category'] = is_array($request->category) ? implode(',', $request->category) : $request->category;
            $validatedData['amenities'] = is_array($request->amenities) ? implode(',', $request->amenities) : $request->amenities;
            $validatedData['room_types'] = is_array($request->room_types) ? implode(',', $request->room_types) : $request->room_types;

            $existingImages = json_decode($hotel->images, true) ?? [];

            if ($request->hasFile('photo')) {
                foreach ($request->file('photo') as $image) {
                    if ($image instanceof \Illuminate\Http\UploadedFile) {
                        $newImagePath = $image->store('images', 'public');
                        $existingImages[] = $newImagePath;
                    }
                }
            }

            //removed images correctly
            if ($request->has('removed_images')) {
                $removedImages = json_decode($request->removed_images, true);
                if (is_array($removedImages)) {
                    $existingImages = array_values(array_diff($existingImages, $removedImages));

                    // Delete removed images from storage
                    foreach ($removedImages as $removedImage) {
                        if (\Storage::disk('public')->exists($removedImage)) {
                            \Storage::disk('public')->delete($removedImage);
                        }
                    }
                }
            }

            $imagePath = $hotel->thumbnail_image;

            if ($request->hasFile('thumbnail_image')) {
                $image = $request->file('thumbnail_image');
                $imageName = time() . '_' . $image->getClientOriginalName(); // Unique name for the file
                $imagePath = $image->storeAs('uploads/images', $imageName, 'public'); // Save in the 'public/uploads/images' directory
            }

            $validatedData['thumbnail_image'] = $imagePath;
            $validatedData['images'] = json_encode($existingImages);

            $hotel->update($validatedData);

            Log::info('Hotel updated successfully', ['hotel_id' => $hotel->id]);

            return redirect()->route('hotelOwner.hotel.show', ['id' => $hotel->id])
                           ->with('success', 'Hotel updated successfully');
                           
        } catch (\Exception $exception) {
            Log::error('Error updating hotel', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return response()->json([
                'error' => 'Something went wrong!',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->delete();
            return redirect()->route('hotel.index')->with('success', 'Hotel updated successfully');
        } catch (\Exception $exception) {
            Log::error('Error deleting hotel', [
                'hotel_id' => $id,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);

            return redirect()->route('district.index')->with('success', 'District saved successfully!');
        }
    }

    public function show($id)
    {
        try {
            $userId = Auth::id();
            $users =  User::find($userId);
            $favorites = Favorite::where('user_id', $userId)->get();
            $hotels = Hotel::with('district','rooms.optionRoom')->findOrFail($id);
            $districts = District::with('province')->get();
            $hotels->images = json_decode($hotels->images, true);
            $bankDetails = BankDetail::where('hotel_id', $id)->get();

            return Inertia::render('HotelOwner/Hotel/Show',[
                'users' => $users,
                'hotels' => $hotels,
                'districts' => $districts,
                'favorites' => $favorites,
                'bankDetails' => $bankDetails,
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
}
