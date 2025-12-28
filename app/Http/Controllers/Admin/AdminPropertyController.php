<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\District;
use Illuminate\Http\Request;
use Inertia\Inertia;


class AdminPropertyController extends Controller
{
    public function index()
    {
        $hotelOwner = User::where('role', 'hotel_owner')->get();
        $propertyRequest = User::where('role', 'hotel_owner')
            ->where('permit_status', 'pending')
            ->with(['hotels' => function ($query) {
                $query->select('id', 'hotel_owner_id', 'name','location', 'created_at');
            }])
            ->get();

        return Inertia::render('Admin/PropertyRequest/PropertyRequest',[
            'hotelOwner' => $hotelOwner,
            'propertyRequest' => $propertyRequest
            ]
        );
    }

    public function show($id)
    {
        $user = User::with('hotels')->find($id);
        $districts = District::all();
        return Inertia::render('Admin/PropertyRequest/ViewPropertyRequest', [
            'user' => $user,
            'districts' => $districts
        ]);
    }


}
