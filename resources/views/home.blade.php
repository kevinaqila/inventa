@extends('layouts.app')

@section('title', 'INVENTA - Sistem Peminjaman Inventaris Kampus')

@section('content')
<div class="max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="relative py-2 sm:py-4 mb-8 sm:mb-10 text-center">
        <div class="absolute inset-0 -top-6 -bottom-6 pointer-events-none -z-10 flex items-center justify-center overflow-hidden">
            <img src="/images/hero-bg.jpg" alt="" class="w-full h-full max-w-4xl object-cover opacity-45 filter saturate-50 [mask-image:radial-gradient(ellipse_at_center,black_25%,transparent_75%)] [-webkit-mask-image:radial-gradient(ellipse_at_center,black_25%,transparent_75%)]">
            <div class="absolute inset-0 bg-white/30 backdrop-blur-[0.5px] [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_75%)] [-webkit-mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_75%)]"></div>
        </div>

        <div class="max-w-2xl mx-auto space-y-3.5 relative z-10">
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 bg-white/95 backdrop-blur-xs px-3.5 py-1.5 rounded-full border border-slate-200 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Jam Buka Loket (08:00 - 16:00)</span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight [text-shadow:_0_1px_14px_rgba(255,255,255,0.95)]">
                Sistem Peminjaman Inventaris Kampus
            </h1>

            <p class="text-xs sm:text-sm text-slate-800 leading-relaxed max-w-xl mx-auto font-semibold [text-shadow:_0_1px_10px_rgba(255,255,255,0.95)]">
                Ajukan kebutuhan peralatan perkuliahan secara mandiri dan cepat. Cukup gunakan akun mahasiswa Anda, pilih barang, dan ambil di loket TU dengan menunjukkan KTM asli.
            </p>

            <div class="pt-2 flex flex-row items-center justify-center gap-2 sm:gap-3 max-w-lg mx-auto w-full">
                <a href="{{ route('loans.create') }}" class="btn-primary flex-[1.4] md:flex-initial py-3 sm:py-3.5 px-4 sm:px-8 text-xs sm:text-sm font-bold shadow-md shadow-teal-700/10 transition active:scale-[0.98] text-center justify-center rounded-full">
                    <span>Isi Formulir Peminjaman</span>
                </a>
                <a href="#daftar-barang" class="flex-1 md:hidden py-3 px-2 text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-full shadow-xs transition active:scale-[0.98] inline-flex items-center justify-center gap-1.5">
                    <span>Cek Ketersediaan</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
        <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col justify-between space-y-5">
            <div>
                <h2 class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-4">Tahapan Peminjaman</h2>

                <div>
                    <div class="flex gap-3.5">
                        <div class="flex flex-col items-center">
                            <div class="w-3.5 h-3.5 rounded-full border-2 border-teal-600 bg-white flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-teal-600"></div>
                            </div>
                            <div class="w-0.5 flex-1 bg-slate-200 my-1"></div>
                        </div>
                        <div class="pb-4">
                            <h3 class="text-sm font-bold text-slate-900 leading-tight">Masuk Akun & Isi Formulir</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Login dengan akun peminjam. Identitas Anda terisi otomatis, tinggal pilih barang inventaris dan ruangan.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3.5">
                        <div class="flex flex-col items-center">
                            <div class="w-3.5 h-3.5 rounded-full border-2 border-teal-600 bg-white flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-teal-600"></div>
                            </div>
                            <div class="w-0.5 flex-1 bg-slate-200 my-1"></div>
                        </div>
                        <div class="pb-4">
                            <h3 class="text-sm font-bold text-slate-900 leading-tight">Datang ke Ruang Tata Usaha</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Tunjukkan kode bukti transaksi atau sebutkan nama / NIM Anda ke petugas loket Tata Usaha.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3.5">
                        <div class="flex flex-col items-center">
                            <div class="w-3.5 h-3.5 rounded-full border-2 border-teal-600 bg-white flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-teal-600"></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 leading-tight">Serahkan KTM & Ambil Barang</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Petugas memeriksa fisik barang inventaris, menyetujui sistem, dan menyerahkan aset untuk digunakan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-amber-50/90 border border-amber-200 flex items-start gap-2.5 text-xs text-amber-900">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="leading-relaxed">
                    <strong class="font-bold">Batas Pengambilan 30 Menit:</strong> Pengajuan otomatis kedaluwarsa jika barang tidak diambil di loket TU dalam waktu 30 menit.
                </div>
            </div>
        </div>

        <div id="daftar-barang" class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col justify-between space-y-3 scroll-mt-20 sm:scroll-mt-24">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Barang Siap Dipinjam</h3>
                <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200">
                    {{ $items->count() }} Tersedia
                </span>
            </div>

            <div class="space-y-2 max-h-[310px] overflow-y-auto pr-1 flex-1">
                @forelse ($items as $item)
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs">
                        <span class="font-bold text-slate-800 block truncate">{{ $item->name }}</span>
                        @if ($item->description)
                            <span class="text-[11px] text-slate-400 block truncate mt-0.5">{{ $item->description }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-8">Saat ini semua barang sedang dipinjam.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
