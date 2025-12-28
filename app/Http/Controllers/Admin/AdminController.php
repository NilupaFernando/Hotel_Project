<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Booking;

class AdminController extends Controller
{
    public function getAdminDashboard()
    {
        try {
            $allUsers = User::count();

            //GET ALL HOTEL COUNT
            $hotels = Hotel::whereHas('user', function ($query) {
                $query->where('permit_status', 'active');
            })->count();

            // GET USER DETAILS
            $superAdminCount = User::where('role', 'admin')->count();
            $userCount = User::where('role', 'user')->count();
            $hotelOwnerCount = User::where('role', 'hotel_owner')->count();
            // Avoid division by zero
            $userPercentage = $allUsers > 0 ? round(($userCount / $allUsers) * 100) : 0;
            $superAdminPercentage = $allUsers > 0 ? round(($superAdminCount / $allUsers) * 100) : 0;
            $hotelOwnerPercentage = $allUsers > 0 ? round(($hotelOwnerCount / $allUsers) * 100) : 0;

            // GET ACCOMMODATION COUNTS
            $accommodationTypes = [
                'Hotel' => Hotel::where('accommodation_type', 'Hotel')->count(),
                'Apartment' => Hotel::where('accommodation_type', 'Appartment')->count(),
                'Villa' => Hotel::where('accommodation_type', 'Villa')->count(),
                'Motel' => Hotel::where('accommodation_type', 'Motel')->count(),
                'Hostel' => Hotel::where('accommodation_type', 'Hostel')->count(),
                'Guest House' => Hotel::where('accommodation_type', 'Guest House')->count(),
                'Resort' => Hotel::where('accommodation_type', 'Resort')->count(),
            ];
            // total count of accomadations
            $totalAccommodations = array_sum($accommodationTypes);

            // cal accommadation precentage (Avoid division by zero)
            $accommodationPercentages = [];
            foreach ($accommodationTypes as $type => $count) {
                $accommodationPercentages[$type] = $totalAccommodations > 0 ? round(($count / $totalAccommodations) * 100) : 0;
            }


            // GET PROPERTY REQUEST DETAILS
            $RejectedPropertyRequests = User::where('role', 'hotel_owner')->where('permit_status' , 'inactive')->count();
            $ApprovedPropertyRequests = User::where('role', 'hotel_owner')->where('permit_status' , 'active')->count();
            $PendingPropertyRequests = User::where('role', 'hotel_owner')->where('permit_status' , 'pending')->count();

            //GET ALL BOOKING COUNT
            $bookings = Booking::where('payment_status','paid')->count();

            return Inertia::render('Admin/Dashboard/Dashboard',
                [
                    'allUsers' => $allUsers,
                    'hotels' => $hotels,
                    'superAdminPercentage' => $superAdminPercentage,
                    'userPercentage' => $userPercentage,
                    'hotelOwnerPercentage' => $hotelOwnerPercentage,
                    'accommodationPercentages' => $accommodationPercentages,
                    'hotelOwnerCount' => $hotelOwnerCount,
                    'RejectedPropertyRequests' => $RejectedPropertyRequests,
                    'ApprovedPropertyRequests' => $ApprovedPropertyRequests,
                    'PendingPropertyRequests' => $PendingPropertyRequests,
                    'bookings' => $bookings,
                ]
            );
        } catch (\Exception $exception) {

        }

    }

}
