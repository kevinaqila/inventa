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

    public function myLoans()
    {
        $userId = auth()->id();
        $userNim = auth()->user()->nim;

        $loans = Loan::with('items')
            ->where(function ($query) use ($userId, $userNim) {
                $query->where('user_id', $userId);
                if ($userNim) {
                    $query->orWhere('borrower_id_number', $userNim);
                }
            })
            ->latest('requested_at')
            ->get();

        $activeLoans = $loans->filter(function ($loan) {
            return in_array($loan->status, ['pending', 'borrowed', 'return_pending']);
        });

        $historyLoans = $loans->filter(function ($loan) {
            return in_array($loan->status, ['returned', 'rejected', 'expired']);
        });

        return view('loans.my', compact('activeLoans', 'historyLoans'));
    }

    public function showReturnForm(string $code)
    {
        $loan = Loan::with('items')
            ->where('loan_code', $code)
            ->firstOrFail();

        if ($loan->user_id !== auth()->id() && $loan->borrower_id_number !== auth()->user()->nim) {
            abort(403, 'Akses dibatasi.');
        }

        if ($loan->status === 'return_pending') {
            return redirect()->route('loans.my')->with('info', 'Pengajuan pengembalian untuk transaksi ini sudah dikirim dan sedang menunggu verifikasi petugas TU.');
        }

        if ($loan->status !== 'borrowed') {
            return redirect()->route('loans.my')->with('error', 'Status peminjaman ini tidak dapat diajukan pengembalian.');
        }

        return view('loans.return', compact('loan'));
    }

    public function submitReturn(Request $request, string $code)
    {
        $loan = Loan::with('items')
            ->where('loan_code', $code)
            ->firstOrFail();

        if ($loan->user_id !== auth()->id() && $loan->borrower_id_number !== auth()->user()->nim) {
            abort(403, 'Akses dibatasi.');
        }

        if ($loan->status !== 'borrowed') {
            return redirect()->route('loans.my')->with('error', 'Transaksi ini tidak dalam status sedang dipinjam.');
        }

        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'return_notes' => 'nullable|string|max:500',
        ], [
            'photo.required' => 'Foto bukti kondisi barang wajib diambil.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $file = $request->file('photo');
        $cleanCode = preg_replace('/[^A-Za-z0-9\-]/', '', $loan->loan_code);
        $filename = 'return_' . strtolower($cleanCode) . '_' . time() . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path('uploads/returns');
        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $file->move($destinationPath, $filename);

        $updateData = [
            'status' => 'return_pending',
            'return_photo_path' => 'uploads/returns/' . $filename,
            'return_requested_at' => now(),
        ];

        if (! empty($validated['return_notes'])) {
            $notesPrefix = $loan->officer_notes ? $loan->officer_notes . "\n\n" : '';
            $updateData['officer_notes'] = $notesPrefix . '[Catatan Peminjam: ' . $validated['return_notes'] . ']';
        }

        $loan->update($updateData);

        return redirect()->route('loans.my')->with('success', 'Bukti pengembalian berhasil diunggah! Silakan bawa fisik barang ke loket TU untuk verifikasi dan serah terima kartu identitas (KTM).');
    }
}
