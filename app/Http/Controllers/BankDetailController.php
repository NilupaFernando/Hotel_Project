<?php

namespace App\Http\Controllers;

use App\Models\BankDetail;
use Illuminate\Http\Request;

class BankDetailController extends Controller
{
    public function index()
    {
        $bankDetails = BankDetail::with(['hotel', 'owner'])->get();
        return inertia('BankDetail/Index', compact('bankDetails'));
    }

    public function create()
    {
        return inertia('BankDetail/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'hotelowner_id' => 'required|exists:users,id',
            'bank_name' => 'required|string|max:255',
            'acc_nomber' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'acc_holder_name' => 'required|string|max:255',
        ]);

        BankDetail::create($validated);

        // return redirect()->route('bank-detail.index')->with('success', 'Bank detail created.');
    }

    public function edit(BankDetail $bankDetail)
    {
        return inertia('BankDetail/Edit', compact('bankDetail'));
    }

    public function update(Request $request, BankDetail $bankDetail)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'hotelowner_id' => 'required|exists:users,id',
            'bank_name' => 'required|string|max:255',
            'acc_nomber' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'acc_holder_name' => 'required|string|max:255',
        ]);

        $bankDetail->update($validated);

        return redirect()->route('bank-detail.index')->with('success', 'Bank detail updated.');
    }

    public function destroy(BankDetail $bankDetail)
    {
        $bankDetail->delete();
        // return redirect()->route('bank-detail.index')->with('success', 'Bank detail deleted.');
    }
}
