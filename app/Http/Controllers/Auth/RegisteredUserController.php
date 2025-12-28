<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\StoreHotelOwnerRequest;
use App\Models\District;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function createHotelOwner(): Response
    {
        $userId = Auth::id();
        $users = User::find($userId);
        $districts = District::with('province')->get();
        return Inertia::render('Auth/HotelOwner/Register',[
            'districts' => $districts,
            'users' => $users
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'permit_status'=> 'active'
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('user.dashboard', absolute: false));
    }


    public function storeHotelOwner(StoreHotelOwnerRequest $request): RedirectResponse
    {
//        dd($request->all());
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

            $validAccommodationTypes = ["Villa", "Homestay", "Chalet", "Business Hotel", "Extended Stay Hotel", "Apartment"];

            if (in_array($validatedData['accommodation_type'], $validAccommodationTypes)) {
                if (!$hotel) {
                    return back()->with('error', 'Hotel not found.');
                }

                $room = Room::create([
                    'hotel_id' => $hotel->id,
                    'type' => $validatedData['accommodation_type'],
                    'bookable_rooms' => 1,
                    'available_rooms' => 1,
                    'max_adult' => $request->input('max_adults', 0),
                    'max_children' => $request->input('max_children', 0),
                    'price_per_adult' => $request->input('price_per_adult', 0),
                    'price_per_child' => $request->input('price_per_child', 0),
                    'features' => $validatedData['features'] ?? null,
                    'free_services' => $validatedData['free_services'] ?? null,
                    'discount' => $validatedData['discount'] ?? 0,
                ]);
            }

            DB::commit();

            return back()->with('status', 'Hotel registered successfully. Awaiting approval.');
            //            return redirect()->to('/')->with('status', 'Registration submitted! Waiting for super admin approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

}
