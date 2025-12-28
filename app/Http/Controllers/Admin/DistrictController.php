<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\District\SaveDistrictRequest;
use App\Http\Requests\District\UpdateDistrictRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Models\District;
use App\Models\province;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use function Pest\PendingCalls\pr;
use function Termwind\render;
use Illuminate\Support\Facades\Auth;


class DistrictController extends Controller
{
    public function save(SaveDistrictRequest $request)
    {
        try {
//            dd($request->all());
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName(); // Unique name for the file
                $imagePath = $image->storeAs('uploads/images', $imageName, 'public'); // Save in the 'public/uploads/images' directory
            }

            $mostlyPopular = $request->has('mostly_popular') && is_array($request->input('mostly_popular'))
                ? implode(',', $request->input('mostly_popular'))
                :  $request->input('mostly_popular');

            $save = District::create([
                'province_id' => $request->input('province_id'),
                'name' => $request->input('name'),
                'about' => $request->input('about'),
                'location' => $request->input('location'),
                'mostly_popular' => $mostlyPopular,
                'travel_season' => $request->input('travel_season'),
                'image' => $imagePath,
            ]);
            if ($save) {
                return redirect()->route('district.index')->with('success', 'District saved successfully!');
            }
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function getAdminDistrict()
    {
//        dd("dfds");
        $districts = District::all();
        $userId = Auth::id();
        $user = User::find($userId);
        return Inertia::render('Admin/District/Index',[
            'districts' => $districts,
            'user' => $user
        ]);
    }

    public function getAdminDistrictCreate()
    {
        try {
            $provinces = Province::all();
            return Inertia::render('Admin/District/create',[
                'provinces' => $provinces,
            ]);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function edit($id)
    {
        try {
            $provinces = Province::all();
            $districts = District::findOrFail($id);
            $getProvincesIdValue = Province::findOrFail($districts->province_id);
            return Inertia::render('Admin/District/edit', [
                'provinces' => $provinces,
                'districts' => $districts,
                'getProvincesIdValue'=>$getProvincesIdValue
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('district.index')->with('success', $exception->getMessage());
        }
    }

    public function update(UpdateDistrictRequest $request, $id)
    {
//        dd($request->all());
        try {
            $district = District::findOrFail($id);

            $imagePath = $district->image;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName(); // Unique name for the file
                $imagePath = $image->storeAs('uploads/images', $imageName, 'public'); // Save in the 'public/uploads/images' directory
            }

            $mostlyPopular = $request->has('MostlyPopular') && is_array($request->input('MostlyPopular'))
                ? implode(',', $request->input('MostlyPopular'))
                :  $request->input('MostlyPopular');

            $update = $district->update([
                'province_id' => $request->input('province_id'),
                'name' => $request->input('name'),
                'about' => $request->input('about'),
                'location' => $request->input('location'),
                'mostly_popular' => $mostlyPopular,
                'travel_season' => $request->input('travelSeason'),
                'image' => $imagePath,
            ]);
            if ($update) {
                return redirect()->route('district.index')->with('success', 'District saved successfully!');
            }
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function delete($id)
    {
//        dd($id);
        try {
            $district = District::findOrFail($id);
            $district->delete();

            return redirect()->route('district.index')->with('success', 'District saved successfully!');
        } catch (\Exception $exception) {
            return redirect()->route('district.index')->with('success', 'District saved successfully!');
        }
    }
}
