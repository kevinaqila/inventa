<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PublicLoanController extends Controller
{
    public function home()
    {
        $items = Item::where('status', 'available')
            ->orderBy('name')
            ->get();

        return view('home', compact('items'));
    }

    public function index()
    {
        if (auth()->check() && auth()->user()->role !== 'mahasiswa') {
            return redirect('/admin');
        }

        $items = Item::where('status', 'available')
            ->orderBy('name')
            ->get();

        return view('loans.create', compact('items'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'mahasiswa') {
            return redirect('/admin');
        }

        $validated = $request->validate([
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:items,id',
            'phone_number' => 'required|string|max:30',
            'lecturer_name' => 'required|string|max:255',
            'destination_building' => 'required|string|max:255',
            'destination_room' => 'required|string|max:255',
            'purpose' => 'required|string',
            'expected_return_at' => 'required|date',
        ], [
            'item_ids.required' => 'Pilih minimal satu barang inventaris yang ingin dipinjam.',
            'item_ids.min' => 'Pilih minimal satu barang inventaris yang ingin dipinjam.',
            'phone_number.required' => 'Nomor WhatsApp wajib diisi.',
            'lecturer_name.required' => 'Nama dosen pengajar wajib diisi.',
            'destination_building.required' => 'Gedung tujuan wajib diisi.',
            'destination_room.required' => 'Ruangan tujuan wajib diisi.',
            'purpose.required' => 'Alasan peminjaman wajib diisi.',
            'expected_return_at.required' => 'Estimasi waktu pengembalian wajib diisi.',
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

        $user = auth()->user();

        if ($user->phone_number !== $validated['phone_number']) {
            $user->update(['phone_number' => $validated['phone_number']]);
        }

        $loan = Loan::create([
            'user_id' => $user->id,
            'loan_code' => $loanCode,
            'borrower_id_number' => $user->nim ?? '-',
            'borrower_name' => $user->name,
            'study_program' => $user->study_program ?? '-',
            'phone_number' => $validated['phone_number'],
            'lecturer_name' => $validated['lecturer_name'],
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
