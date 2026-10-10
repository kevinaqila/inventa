<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVENTA - Sistem Peminjaman Inventaris Kampus</title>
    <link rel="icon" type="image/svg+xml" href="/images/inventa-logo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .btn-primary {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background-color: #0d9488;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.875rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2), 0 2px 4px -2px rgba(13, 148, 136, 0.2);
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-align: center;
        }
        .btn-primary:hover {
            background-color: #0f766e;
        }
        .btn-primary:active {
            transform: scale(0.99);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between antialiased">
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-1 group">
                <img src="/images/inventa-logo.svg" alt="INVENTA Logo" class="w-10 h-10 group-hover:scale-105 transition drop-shadow-sm">
                <img src="/images/inventa-text.svg" alt="INVENTA" class="h-5 w-auto">
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-slate-700 bg-teal-50 border border-teal-200 px-3 py-1.5 rounded-xl hidden md:inline-block">
                            {{ auth()->user()->name }} ({{ auth()->user()->nim }})
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-xl transition">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 px-3 py-1.5 rounded-xl hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 px-3.5 py-1.5 rounded-xl shadow-sm transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Jam Buka (08:00 - 16:00)</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Sistem Peminjaman Inventaris Kampus
                    </h1>
                    <p class="text-sm text-slate-600 mt-3 leading-relaxed max-w-xl">
                        Ajukan kebutuhan peralatan perkuliahan secara mandiri dan cepat. Cukup gunakan akun mahasiswa Anda, pilih barang, dan ambil di loket TU dengan menunjukkan KTM asli.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-sm space-y-4">
                    <h2 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Tahapan Peminjaman</h2>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                1
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Masuk Akun & Isi Formulir</h3>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                                    Login dengan akun peminjam. Identitas Anda terisi otomatis, tinggal pilih barang inventaris dan ruangan.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                2
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Datang ke Ruang Tata Usaha</h3>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                                    Tunjukkan kode bukti transaksi atau sebutkan nama / NIM Anda ke petugas loket Tata Usaha.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                3
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Serahkan KTM & Ambil Barang</h3>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                                    Petugas memeriksa fisik barang inventaris, menyetujui sistem, dan menyerahkan aset untuk digunakan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3 text-xs text-amber-900">
                    <x-heroicon-o-clock class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                    <div class="leading-relaxed">
                        <span class="font-bold block mb-0.5 text-amber-900">Batas Waktu Pengambilan: 30 Menit</span>
                        Setelah formulir disubmit, pengajuan akan otomatis kedaluwarsa apabila barang tidak diambil di loket TU dalam waktu 30 menit.
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6">
                <div>
                    <a href="{{ route('loans.create') }}" class="btn-primary py-4 text-base">
                        <span>Isi Formulir Peminjaman</span>
                    </a>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Barang Siap Dipinjam</h3>
                        <span class="text-xs font-semibold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-200">
                            {{ $items->count() }} Tersedia
                        </span>
                    </div>

                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        @forelse ($items as $item)
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80 text-xs">
                                <span class="font-bold text-slate-800 block truncate">{{ $item->name }}</span>
                                @if ($item->description)
                                    <span class="text-[11px] text-slate-400 block truncate mt-0.5">{{ $item->description }}</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">Saat ini semua barang sedang dipinjam.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} <strong class="text-slate-600 font-semibold">INVENTA</strong> - Sistem Inventaris Kampus
    </footer>
</body>
</html>
