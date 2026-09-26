<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicLoanController extends Controller
{
    public function index()
    {
        $items = Item::where('status', 'available')
            ->orderBy('name')
            ->get();

        return view('loans.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:items,id',
            'borrower_id_number' => 'required|string|max:50',
            'borrower_name' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'destination_building' => 'required|string|max:255',
            'destination_room' => 'required|string|max:255',
            'purpose' => 'required|string',
            'expected_return_at' => 'required|date',
        ], [
            'item_ids.required' => 'Pilih minimal satu barang inventaris yang ingin dipinjam.',
            'item_ids.min' => 'Pilih minimal satu barang inventaris yang ingin dipinjam.',
        ]);

        $unavailableItems = Item::whereIn('id', $validated['item_ids'])
            ->where('status', '!=', 'available')
            ->pluck('name')
            ->toArray();

        if (!empty($unavailableItems)) {
            return back()->withErrors([
                'item_ids' => 'Barang berikut sedang tidak tersedia: ' . implode(', ', $unavailableItems)
            ])->withInput();
        }

        $loanCode = 'LN-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $loan = Loan::create([
            'loan_code' => $loanCode,
            'borrower_id_number' => $validated['borrower_id_number'],
            'borrower_name' => $validated['borrower_name'],
            'study_program' => $validated['study_program'],
            'phone_number' => $validated['phone_number'],
            'destination_building' => $validated['destination_building'],
            'destination_room' => $validated['destination_room'],
            'purpose' => $validated['purpose'],
            'requested_at' => now(),
            'expected_return_at' => $validated['expected_return_at'],
            'status' => 'pending',
        ]);

        $loan->items()->attach($validated['item_ids']);

        return redirect()->route('loans.success', ['code' => $loan->loan_code]);
    }

    public function success(string $code)
    {
        $loan = Loan::with('items')
            ->where('loan_code', $code)
            ->firstOrFail();

        return view('loans.success', compact('loan'));
    }
}
