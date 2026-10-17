@extends('layouts.app')

@section('title', ($loan->status === 'returned' ? 'Peminjaman Selesai' : ($loan->status === 'borrowed' ? 'Peminjaman Aktif' : 'Detail Transaksi')) . ' - ' . $loan->loan_code . ' - INVENTA')

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
<div class="max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-12 flex-1 flex flex-col items-center justify-center">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-10 w-full">
        <div class="text-center">
            @if($loan->status === 'returned')
                <div class="w-10 h-10 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <x-heroicon-o-check class="w-5 h-5" />
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Peminjaman Selesai</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Barang inventaris telah diverifikasi dan diterima kembali oleh petugas Tata Usaha.
                </p>
            @elseif($loan->status === 'borrowed')
                <div class="w-10 h-10 rounded-md bg-teal-50 border border-teal-200 text-teal-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Barang Sedang Dipinjam</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Barang saat ini dalam pemakaian aktif Anda untuk kegiatan perkuliahan.
                </p>
            @elseif($loan->status === 'return_pending')
                <div class="w-10 h-10 rounded-md bg-sky-50 border border-sky-200 text-sky-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Menunggu Verifikasi Pengembalian</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Foto bukti fisik telah diunggah. Silakan bawa barang fisik ke loket TU.
                </p>
            @elseif($loan->status === 'rejected')
                <div class="w-10 h-10 rounded-md bg-rose-50 border border-rose-200 text-rose-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Ditolak</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Permohonan peminjaman ini tidak dapat disetujui oleh petugas TU.
                </p>
            @elseif($loan->status === 'expired')
                <div class="w-10 h-10 rounded-md bg-slate-100 border border-slate-200 text-slate-500 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Kedaluwarsa</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Batas waktu 30 menit ke loket TU telah terlewati.
                </p>
            @else
                <div class="w-10 h-10 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <x-heroicon-o-check class="w-5 h-5" />
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Berhasil Dikirim!</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Data pengajuan kamu sudah tercatat di sistem.
                </p>
            @endif
        </div>

        <div class="my-6 sm:my-8 p-4 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="grid grid-cols-2 gap-3 pb-4 border-b border-slate-200">
                <div>
                    <span class="receipt-label">Kode Transaksi</span>
                    <span class="text-sm sm:text-xl font-extrabold text-teal-700 tracking-wide block">{{ $loan->loan_code }}</span>
                </div>
                <div class="text-right">
                    @if($loan->status === 'returned')
                        <span class="receipt-label">Waktu Pengembalian</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700 block">{{ $loan->returned_at ? $loan->returned_at->format('d M Y, H:i') . ' WIB' : '-' }}</span>
                    @elseif($loan->status === 'borrowed' || $loan->status === 'return_pending')
                        <span class="receipt-label">Batas Waktu Pengembalian</span>
                        <span class="text-xs sm:text-sm font-semibold {{ $loan->expected_return_at && $loan->expected_return_at->isPast() ? 'text-rose-600 font-bold' : 'text-slate-700' }} block">
                            {{ $loan->expected_return_at ? $loan->expected_return_at->format('d M Y, H:i') . ' WIB' : '-' }}
                        </span>
                    @else
                        <span class="receipt-label">Waktu Pengajuan</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700 block">{{ $loan->requested_at ? $loan->requested_at->format('d M Y, H:i') . ' WIB' : '-' }}</span>
                    @endif
                </div>
            </div>

            <div class="space-y-2 pb-4 border-b border-slate-200">
                <div class="flex items-center justify-between gap-2">
                    <span class="receipt-label">Barang Dipinjam</span>

                    @if($loan->status === 'returned')
                        <span class="text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-md font-bold whitespace-nowrap">
                            Selesai (Kondisi: {{ $loan->return_condition === 'damaged' ? 'Rusak' : 'Baik' }})
                        </span>
                    @elseif($loan->status === 'borrowed')
                        <span class="text-[11px] text-teal-700 bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded-md font-bold whitespace-nowrap">
                            Sedang Dipinjam
                        </span>
                    @elseif($loan->status === 'return_pending')
                        <span class="text-[11px] text-sky-700 bg-sky-50 border border-sky-200 px-2.5 py-0.5 rounded-md font-bold whitespace-nowrap">
                            Menunggu Verifikasi TU
                        </span>
                    @elseif($loan->status === 'rejected')
                        <span class="text-[11px] text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-md font-bold whitespace-nowrap">
                            Ditolak
                        </span>
                    @elseif($loan->status === 'expired')
                        <span class="text-[11px] text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-md font-bold whitespace-nowrap">
                            Kedaluwarsa
                        </span>
                    @else
                        <span class="text-[11px] text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-md font-bold whitespace-nowrap">
                            Menunggu ACC TU
                        </span>
                    @endif
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
                    <span class="receipt-value">{{ $loan->borrower_name ?: '-' }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">{{ $loan->borrower_id_number ?: '-' }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">{{ $loan->study_program ?: '-' }}</span>
                </div>

                <div>
                    <span class="receipt-label">Lokasi</span>
                    <span class="receipt-value">{{ $loan->destination_building ?: '-' }} &mdash; {{ $loan->destination_room ?: '-' }}</span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">WhatsApp: {{ $loan->phone_number ?: '-' }}</span>
                </div>

                <div>
                    <span class="receipt-label">Dosen Pengajar</span>
                    <span class="receipt-value">{{ $loan->lecturer_name ?: '-' }}</span>
                </div>

                <div>
                    <span class="receipt-label">Waktu Peminjaman</span>
                    <span class="receipt-value">{{ $loan->borrowed_at ? $loan->borrowed_at->format('d M Y, H:i') . ' WIB' : ($loan->requested_at ? $loan->requested_at->format('d M Y, H:i') . ' WIB' : '-') }}</span>
                </div>

                @if($loan->return_photo_path)
                    <div class="col-span-2 pt-3 border-t border-slate-200">
                        <span class="receipt-label">Foto Bukti Pengembalian</span>
                        <div class="mt-2 flex items-center gap-3">
                            <a href="{{ asset($loan->return_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-teal-700 transition shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Buka Foto Dokumentasi Fisik</span>
                            </a>
                            @if($loan->return_notes)
                                <span class="text-xs text-slate-500 italic">"{{ $loan->return_notes }}"</span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="col-span-2 pt-3 border-t border-slate-200">
                    <span class="receipt-label">Alasan Peminjaman</span>
                    <p class="text-sm text-slate-700 leading-relaxed mt-0.5">{{ $loan->purpose ?: '-' }}</p>
                </div>
            </div>
        </div>

        @if($loan->status === 'returned')
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-start gap-3 text-xs text-emerald-950 mb-8">
                <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-emerald-900">Peminjaman Telah Selesai</span>
                    Barang telah dikembalikan ke ruang Tata Usaha dan dinyatakan selesai pada <strong>{{ $loan->returned_at ? $loan->returned_at->format('d M Y, H:i') : '-' }} WIB</strong> dengan kondisi fisik <strong>{{ $loan->return_condition === 'damaged' ? 'Rusak' : 'Baik' }}</strong>. Jaminan kartu identitas telah diserahkan kembali.
                </div>
            </div>
        @elseif($loan->status === 'borrowed')
            <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200 flex items-start gap-3 text-xs text-teal-950 mb-8">
                <x-heroicon-o-information-circle class="w-5 h-5 text-teal-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-teal-900">Barang Sedang Digunakan</span>
                    Harap jaga kondisi barang inventaris dan kembalikan ke loket TU tepat waktu sebelum <strong>{{ $loan->expected_return_at ? $loan->expected_return_at->format('d M Y, H:i') . ' WIB' : '-' }}</strong>.
                </div>
            </div>
        @elseif($loan->status === 'return_pending')
            <div class="p-4 rounded-2xl bg-sky-50 border border-sky-200 flex items-start gap-3 text-xs text-sky-950 mb-8">
                <x-heroicon-o-information-circle class="w-5 h-5 text-sky-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-sky-900">Menunggu Verifikasi Loket TU</span>
                    Foto dokumentasi fisik telah tersimpan. Silakan serahkan unit barang ke loket TU untuk verifikasi akhir dan pengambilan kartu identitas (KTM) Anda.
                </div>
            </div>
        @elseif($loan->status === 'rejected')
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-start gap-3 text-xs text-rose-950 mb-8">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-rose-900">Pengajuan Tidak Disetujui</span>
                    {{ $loan->rejection_note ?: 'Pengajuan ini ditolak oleh petugas TU karena kuota peminjaman penuh atau barang sedang dibutuhkan untuk kegiatan jurusan.' }}
                </div>
            </div>
        @elseif($loan->status === 'expired')
            <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 flex items-start gap-3 text-xs text-slate-700 mb-8">
                <x-heroicon-o-clock class="w-5 h-5 text-slate-500 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-slate-900">Pengajuan Kedaluwarsa</span>
                    Permohonan ini otomatis dibatalkan sistem karena tidak ada konfirmasi pengambilan fisik di loket TU dalam batas waktu 30 menit.
                </div>
            </div>
        @else
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3 text-xs text-amber-900 mb-8">
                <x-heroicon-o-information-circle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-amber-900">Langkah Pengambilan Barang</span>
                    Tunjukkan <strong>Transaksi</strong> ini ke petugas di <strong>Ruang TU</strong> dalam batas waktu <strong>30 menit</strong> dengan membawa <strong>Kartu Identitas asli</strong>.
                </div>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            @if($loan->status === 'borrowed')
                <a href="{{ route('loans.return', $loan->loan_code) }}" class="btn-primary flex-1 w-full py-3.5 text-sm font-bold shadow-md shadow-teal-700/10">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Ajukan Pengembalian</span>
                </a>
                <a href="{{ route('loans.my') }}" class="w-full sm:w-auto text-center px-5 py-3.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Kembali
                </a>
            @elseif($loan->status === 'returned')
                <a href="{{ route('loans.create') }}" class="btn-primary flex-1 w-full py-3.5 text-sm font-bold shadow-md shadow-teal-700/10">
                    <span>Pinjam Barang Baru</span>
                </a>
                <a href="{{ route('loans.my') }}" class="w-full sm:w-auto text-center px-5 py-3.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Kembali ke Riwayat
                </a>
            @elseif($loan->status === 'return_pending')
                <a href="{{ route('loans.my') }}" class="w-full text-center py-3.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                    Kembali ke Riwayat
                </a>
            @elseif($loan->status === 'rejected' || $loan->status === 'expired')
                <a href="{{ route('loans.create') }}" class="btn-primary flex-1 w-full py-3.5 text-sm font-bold shadow-md shadow-teal-700/10">
                    <span>Ajukan Peminjaman Baru</span>
                </a>
                <a href="{{ route('loans.my') }}" class="w-full sm:w-auto text-center px-5 py-3.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Kembali ke Riwayat
                </a>
            @else
                <a href="{{ route('loans.my') }}" class="btn-primary flex-1 w-full py-3.5 text-sm font-bold shadow-md shadow-teal-700/10">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Lihat Status Peminjaman</span>
                </a>
                <a href="{{ route('loans.create') }}" class="w-full sm:w-auto text-center px-5 py-3.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Pinjam Barang Lain
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
