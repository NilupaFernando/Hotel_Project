<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Hotel\StoreHotelOwnerRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Models\District;
use App\Models\Favorite;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class HotelController extends Controller
{
    public function index()
    {
        try {
            $hotels = Hotel::whereHas('user', function ($query) {
                $query->where('permit_status', 'active');
            })
                ->latest()
                ->get();

//            dd($hotels);

            $userId = Auth::id();
            $users = User::find($userId);
            $userCount = User::where('role', 'user')->count();
            $favorites = Favorite::where('user_id', $userId)->get();

            return Inertia::render('Admin/Hotel/Index',[
                'userCount' => $userCount,
                'users' => $users,
                'hotels' => $hotels,
                'favorites' =>$favorites
            ]);
        } catch (\Exception $exception) {
            Log::info('Hotel created successfully', [$exception]);
        }
    }

    public function create(){
        try {
            $districts = District::with('province')->get();
            return Inertia::render('Admin/Hotel/Create',[
                'districts' => $districts,
            ]);

        } catch (\Exception $exception) {
            Log::info('Hotel created successfully', [$exception]);
            return Inertia::render('Admin/Hotel/Index');
        }
    }

    public function store(StoreHotelOwnerRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('uploads/images', $imageName, 'public');
            }

            $user = User::create([
                'name' => $validatedData['nameAccount'],
                'email' => $validatedData['emailAccount'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'hotel_owner',
                'permit_status' => 'active',
                'registration_number' => $validatedData['registration_number'],
                'image'=>$imagePath
            ]);

            event(new Registered($user));

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

            $validatedData['hotel_owner_id'] = $user->id;

            $hotel = Hotel::create($validatedData);

            DB::commit();

            return back()->with('status', 'Hotel registered successfully. Awaiting approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $hotel = Hotel::with('district')->findOrFail($id);
            $districts = District::with('province')->get();

            $hotel->images = json_decode($hotel->images, true);

            $hotel->category = explode(',', $hotel->category);
            $hotel->amenities = explode(',', $hotel->amenities);
            $hotel->room_types = explode(',', $hotel->room_types);

            return Inertia::render('Admin/Hotel/edit',[
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

            return redirect()->route('hotel.index')->with('success', 'Hotel updated successfully');
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
            $users = User::find($userId);
            $favorites = Favorite::where('user_id', $userId)->get();
            $hotels = Hotel::with('district','rooms.optionRoom')->findOrFail($id);
            $districts = District::with('province')->get();
            $hotels->images = json_decode($hotels->images, true);

            return Inertia::render('Admin/Hotel/Show',[
                'users' => $users,
                'hotels' => $hotels,
                'districts' => $districts,
                'favorites' => $favorites,
            ]);
//            return Inertia::render('Admin/Hotel/Show');
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
