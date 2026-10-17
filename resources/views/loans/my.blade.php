@extends('layouts.app')

@section('title', 'Peminjaman Saya - INVENTA')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
    <div class="mb-4">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <h2 class="text-base font-bold text-slate-900">Sedang Berjalan ({{ $activeLoans->count() }})</h2>
        </div>

        @if($activeLoans->isEmpty())
            <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-8 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Tidak ada peminjaman aktif</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Saat ini Anda tidak memiliki barang yang sedang dipinjam atau menunggu persetujuan.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach($activeLoans as $loan)
                    <div class="bg-white rounded-3xl border {{ $loan->isOverdue() ? 'border-rose-300 ring-2 ring-rose-100' : 'border-slate-200' }} shadow-sm p-5 sm:p-6 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kode Transaksi</span>
                                    <span class="text-base font-extrabold text-slate-900 font-mono tracking-tight">{{ $loan->loan_code }}</span>
                                </div>

                                <div>
                                    @if($loan->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu ACC TU
                                        </span>
                                    @elseif($loan->status === 'return_pending')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 border border-sky-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($loan->isOverdue())
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                            Terlambat Kembali
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Sedang Dipinjam
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="my-3 space-y-1.5">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Barang Inventaris:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($loan->items as $item)
                                        <span class="text-xs font-bold text-slate-800 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg">
                                            {{ $item->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50/70 p-3 rounded-2xl border border-slate-100 text-slate-600 mt-3">
                                <div>
                                    <span class="text-[10px] text-slate-400 block font-medium">Lokasi Pakai</span>
                                    <span class="font-bold text-slate-800">{{ $loan->destination_building }} &bull; {{ $loan->destination_room }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block font-medium">Dosen Pengajar</span>
                                    <span class="font-bold text-slate-800 truncate block">{{ $loan->lecturer_name }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block font-medium">Waktu Pinjam</span>
                                    <span class="font-semibold text-slate-700">{{ $loan->requested_at->format('d M, H:i') }} WIB</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block font-medium">Batas Kembali</span>
                                    <span class="font-bold {{ $loan->isOverdue() ? 'text-rose-600 font-extrabold' : 'text-slate-800' }}">
                                        {{ $loan->expected_return_at->format('d M, H:i') }} WIB
                                    </span>
                                </div>
                            </div>

                            @if($loan->status === 'pending')
                                <div class="mt-3 p-3 rounded-xl bg-amber-50/80 border border-amber-200/80 text-[11px] text-amber-800 leading-relaxed flex items-start gap-2">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Segera ke ruang Tata Usaha dan tunjukkan kode ini dalam <strong>30 menit</strong> dengan membawa KTM asli.</span>
                                </div>
                            @elseif($loan->status === 'return_pending')
                                <div class="mt-3 p-3 rounded-xl bg-sky-50/80 border border-sky-200/80 text-[11px] text-sky-900 leading-relaxed space-y-2">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-sky-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div>
                                            <strong>Foto bukti pengembalian telah diunggah</strong> pada {{ $loan->return_requested_at ? $loan->return_requested_at->format('d M Y, H:i') : now()->format('H:i') }} WIB.
                                            <p class="mt-0.5 text-sky-800">Serahkan fisik barang ke loket TU untuk verifikasi akhir dan pengambilan kartu identitas KTM Anda.</p>
                                        </div>
                                    </div>
                                    @if($loan->return_photo_path)
                                        <div class="pt-1 flex items-center gap-2">
                                            <a href="{{ asset($loan->return_photo_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-sky-700 bg-white px-2.5 py-1 rounded-lg border border-sky-200 hover:bg-sky-50 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span>Lihat Foto Bukti</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                            <a href="{{ route('loans.success', $loan->loan_code) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-xl hover:bg-slate-100 transition">
                                Lihat Bukti Struk
                            </a>

                            @if($loan->status === 'borrowed')
                                <a href="{{ route('loans.return', $loan->loan_code) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Ajukan Pengembalian</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div>
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-base font-bold text-slate-800">Riwayat Selesai ({{ $historyLoans->count() }})</h2>
        </div>

        @if($historyLoans->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center text-xs text-slate-400">
                Belum ada riwayat transaksi peminjaman yang selesai.
            </div>
        @else
            <div class="space-y-3">
                @foreach($historyLoans as $loan)
                    <div class="bg-white rounded-2xl border border-slate-200/90 hover:border-slate-300 shadow-xs p-4 sm:p-5 transition">
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                            <div>
                                <span class="font-mono font-bold text-sm text-slate-900 tracking-tight block">{{ $loan->loan_code }}</span>
                                <span class="text-[11px] text-slate-400 font-medium block mt-0.5">
                                    {{ ($loan->returned_at ?? $loan->requested_at)?->format('d M Y, H:i') }} WIB
                                </span>
                            </div>

                            <div class="shrink-0 text-right">
                                @if($loan->status === 'returned')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Selesai {{ $loan->return_condition === 'damaged' ? '(Rusak)' : '' }}</span>
                                    </span>
                                @elseif($loan->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Ditolak</span>
                                    </span>
                                @elseif($loan->status === 'expired')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Kedaluwarsa</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mr-1">Barang:</span>
                                @foreach($loan->items as $item)
                                    <span class="inline-flex items-center text-xs font-medium text-slate-700 bg-slate-100/80 border border-slate-200/80 px-2.5 py-1 rounded-lg">
                                        {{ $item->name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="shrink-0 flex items-center justify-end">
                                <a href="{{ route('loans.success', $loan->loan_code) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 transition">
                                    <span>Detail Transaksi</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
