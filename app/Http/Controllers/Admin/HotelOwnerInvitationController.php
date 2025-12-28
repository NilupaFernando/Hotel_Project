<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HotelOwnerInvitation\UpdateHotelOwnerRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HotelOwnerInvitationController extends Controller
{
    public function index()
    {
        try {
            $invitations = User::where('role', 'hotel_owner')->get();

            return Inertia::render('Admin/HotelRegisterAdmin/Index',[
                'invitations'=>$invitations
            ]);
        }catch (\Exception $exception) {
            Log::error('Error in HotelOwnerInvitationController@index', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function update(UpdateHotelOwnerRequest $request, string $id)
    {
        try {
            $hotelOwner = User::findOrFail($id);
            $hotelOwner->update([
                'permit_status' => $request->input('permit_status'),
            ]);

            return redirect()->back()->with('success', 'Permit status updated successfully!');

        } catch (\Exception $exception) {
            Log::error('Error updating room detail', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return back()->with('error', 'Something went wrong!');
        }
    }
}
