@extends('layouts.app')

@section('title', 'Ajukan Pengembalian - ' . $loan->loan_code . ' - INVENTA')

@push('styles')
<style>
    .receipt-label {
        display: block;
        font-size: 0.75rem;
        margin-bottom: 0.125rem;
        font-weight: 500;
        color: #94a3b8;
    }
    .receipt-value {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-10 flex-1 flex flex-col items-center justify-center">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-10 w-full">
        <div class="text-center">
            <div class="w-10 h-10 rounded-md bg-teal-50 border border-teal-200 text-teal-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Ajukan Pengembalian</h1>
            <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                Jepret kondisi fisik barang inventaris menggunakan kamera HP Anda saat ini sebagai bukti dokumentasi sistem.
            </p>
        </div>

        <div class="my-6 sm:my-8 p-4 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="grid grid-cols-2 gap-3 pb-4 border-b border-slate-200">
                <div>
                    <span class="receipt-label">Kode Transaksi</span>
                    <span class="text-sm sm:text-xl font-extrabold text-teal-700 tracking-wide block">{{ $loan->loan_code }}</span>
                </div>
                <div class="text-right">
                    <span class="receipt-label">Batas Waktu Pengembalian</span>
                    <span class="text-xs sm:text-sm font-semibold {{ $loan->expected_return_at && $loan->expected_return_at->isPast() ? 'text-rose-600 font-bold' : 'text-slate-700' }} block">
                        {{ $loan->expected_return_at ? $loan->expected_return_at->format('d M Y, H:i') . ' WIB' : '-' }}
                    </span>
                </div>
            </div>

            <div class="space-y-2 pb-4 border-b border-slate-200">
                <div class="flex items-center justify-between gap-2">
                    <span class="receipt-label">Barang yang Dikembalikan</span>
                    <span class="text-[11px] text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md font-semibold whitespace-nowrap">Sedang Dipinjam</span>
                </div>
                <div class="space-y-2">
                    @foreach ($loan->items as $item)
                        <div class="py-1">
                            <span class="font-bold text-slate-900 block text-sm leading-snug">{{ $item->name }}</span>
                            @if($item->description)
                                <span class="text-xs text-slate-400 block mt-0.5">{{ $item->description }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-4 gap-y-4">
                <div>
                    <span class="receipt-label">Peminjam</span>
                    <span class="receipt-value">{{ $loan->borrower_name ?: auth()->user()->name }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">{{ $loan->borrower_id_number ?: auth()->user()->nim }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">{{ $loan->study_program ?: (auth()->user()->study_program ?? '-') }}</span>
                </div>

                <div>
                    <span class="receipt-label">Lokasi Pemakaian</span>
                    <span class="receipt-value">{{ $loan->destination_building ?: '-' }} &mdash; {{ $loan->destination_room ?: '-' }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">WhatsApp: {{ $loan->phone_number ?: (auth()->user()->phone_number ?: '-') }}</span>
                </div>

                <div>
                    <span class="receipt-label">Dosen Pengajar</span>
                    <span class="receipt-value">{{ $loan->lecturer_name ?: '-' }}</span>
                </div>

                <div>
                    <span class="receipt-label">Waktu Peminjaman</span>
                    <span class="receipt-value">{{ $loan->borrowed_at ? $loan->borrowed_at->format('d M Y, H:i') . ' WIB' : ($loan->created_at ? $loan->created_at->format('d M Y, H:i') . ' WIB' : '-') }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('loans.return.submit', $loan->loan_code) }}" method="POST" enctype="multipart/form-data" id="return-form" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="receipt-label !text-slate-700 !font-bold">
                    Foto Fisik Barang <span class="text-rose-500">*</span>
                </label>

                <input 
                    type="file" 
                    id="camera-input" 
                    name="photo" 
                    accept="image/*" 
                    capture="environment" 
                    class="hidden" 
                    required 
                    onchange="handlePhotoSelected(event)"
                >

                <div id="camera-trigger-box" onclick="document.getElementById('camera-input').click()" class="border-2 border-dashed border-slate-300 hover:border-teal-500 bg-slate-50/70 hover:bg-teal-50/40 rounded-2xl p-6 sm:p-8 text-center cursor-pointer transition group space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-teal-600 text-white flex items-center justify-center mx-auto shadow-sm group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-slate-800 block group-hover:text-teal-700 transition">Buka Kamera & Jepret Foto</span>
                        
                    </div>
                </div>

                <div id="photo-preview-box" class="hidden space-y-3">
                    <div class="relative rounded-2xl overflow-hidden border border-slate-300 bg-slate-900 shadow-sm max-h-96 flex items-center justify-center">
                        <img id="photo-preview-img" src="" alt="Preview Bukti Foto" class="w-full h-auto object-contain max-h-96">
                        
                        <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur-sm text-white text-[11px] font-mono px-3 py-1.5 rounded-lg border border-white/20 flex items-center gap-1.5 shadow">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span id="preview-timestamp">{{ now()->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs text-emerald-600 font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Foto berhasil diambil</span>
                        </span>
                        <button type="button" onclick="document.getElementById('camera-input').click()" class="text-xs font-bold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-xl border border-slate-300 transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Ambil Ulang Foto</span>
                        </button>
                    </div>
                </div>

                @error('photo')
                    <p class="text-xs text-rose-600 font-semibold mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="return_notes" class="receipt-label !text-slate-700 !font-bold">
                    Catatan Peminjam <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <textarea 
                    name="return_notes" 
                    id="return_notes" 
                    rows="2" 
                    class="form-input text-xs leading-relaxed"
                >{{ old('return_notes') }}</textarea>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed flex items-start gap-3">
                <x-heroicon-o-information-circle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-amber-900">Langkah Verifikasi Fisik di Loket:</span>
                    Setelah menekan tombol di bawah, bawa fisik barang ke loket Tata Usaha. Petugas akan mencocokkan kelengkapan fisik dengan sistem sebelum mengembalikan jaminan kartu identitas (KTM) Anda.
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3">
                <button type="submit" id="btn-submit" disabled class="btn-primary flex-1 w-full py-3.5 text-sm font-bold shadow-md shadow-teal-700/10 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>Kirim Pengajuan Pengembalian</span>
                </button>
                <a href="{{ route('loans.my') }}" class="w-full sm:w-auto text-center px-5 py-3.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function handlePhotoSelected(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran foto terlalu besar. Maksimal 5 MB.');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('photo-preview-img');
            previewImg.src = e.target.result;

            document.getElementById('camera-trigger-box').classList.add('hidden');
            document.getElementById('photo-preview-box').classList.remove('hidden');

            document.getElementById('btn-submit').removeAttribute('disabled');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
@endsection
