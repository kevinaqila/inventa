<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Berhasil - INVENTA</title>
    <link rel="icon" type="image/svg+xml" href="/images/inventa-logo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between antialiased">
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <a href="{{ route('loans.create') }}" class="flex items-center gap-1 group">
                <img src="/images/inventa-logo.svg" alt="INVENTA Logo" class="w-10 h-10 group-hover:scale-105 transition drop-shadow-sm">
                <img src="/images/inventa-text.svg" alt="INVENTA" class="h-5 w-auto">
            </a>
            <a href="{{ route('loans.create') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 px-3 py-2 rounded-xl border border-teal-200 transition flex items-center gap-1.5">
                <x-heroicon-o-plus class="w-3.5 h-3.5" />
                <span>Pinjam Barang Lain</span>
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-12 flex-1 flex flex-col items-center justify-center">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10 w-full">
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <x-heroicon-o-check class="w-8 h-8" />
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Berhasil Dikirim!</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Data pengajuan kamu sudah tercatat di sistem INVENTA dan masuk ke antrean loket Tata Usaha.
                </p>
            </div>

            <div class="my-8 p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-2">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Kode Transaksi</span>
                        <span class="text-lg sm:text-xl font-mono font-extrabold text-teal-700 tracking-wide">{{ $loan->loan_code }}</span>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-xs text-slate-400 block">Waktu Pengajuan</span>
                        <span class="text-xs font-semibold text-slate-700">{{ $loan->requested_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                <div class="space-y-2 pb-4 border-b border-slate-200 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Daftar Barang yang Dipinjam</span>
                        <span class="text-[11px] text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md font-semibold">Dalam Antrean</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ($loan->items as $item)
                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex items-center justify-between shadow-xs">
                                <div class="min-w-0 pr-2">
                                    <span class="font-bold text-slate-900 block text-xs truncate">{{ $item->name }}</span>
                                    @if($item->description)
                                        <span class="text-[11px] text-slate-400 block truncate">{{ $item->description }}</span>
                                    @endif
                                </div>
                                <span class="font-mono text-[10px] font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2 py-1 rounded-md shrink-0">{{ $item->code }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="space-y-1">
                        <span class="text-slate-400 block font-medium">Identitas Peminjam</span>
                        <span class="text-sm font-bold text-slate-900 block">{{ $loan->borrower_name }}</span>
                        <span class="text-slate-500 text-[11px] block">NIM: {{ $loan->borrower_id_number }} &bull; {{ $loan->study_program }}</span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400 block font-medium">Nomor WhatsApp</span>
                        <span class="text-sm font-bold text-slate-900 block">{{ $loan->phone_number }}</span>
                    </div>

                    <div class="space-y-1 pt-2 sm:pt-0">
                        <span class="text-slate-400 block font-medium">Lokasi Pemakaian</span>
                        <span class="text-sm font-semibold text-slate-800 block">{{ $loan->destination_building }} &mdash; {{ $loan->destination_room }}</span>
                    </div>

                    <div class="space-y-1 pt-2 sm:pt-0">
                        <span class="text-slate-400 block font-medium">Estimasi Waktu Pengembalian</span>
                        <span class="text-sm font-semibold text-slate-800 block">{{ $loan->expected_return_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-200 text-xs">
                    <span class="text-slate-400 block font-medium mb-0.5">Keperluan:</span>
                    <p class="text-slate-700 italic bg-white p-3 rounded-xl border border-slate-200 leading-relaxed">
                        &ldquo;{{ $loan->purpose }}&rdquo;
                    </p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3 text-xs text-amber-900 mb-8">
                <x-heroicon-o-information-circle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-amber-900">Langkah Pengambilan Barang</span>
                    Tunjukkan <strong>Kode Transaksi</strong> ini ke petugas di <strong>Ruang TU</strong> dalam batas waktu <strong>30 menit</strong> dengan membawa <strong>KTM asli</strong>.
                </div>
            </div>

            <div>
                <a href="{{ route('loans.create') }}" class="w-full py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-2xl shadow-md shadow-teal-600/20 transition flex items-center justify-center gap-2 active:scale-[0.99] text-center">
                    <span>Selesai & Kembali ke Formulir</span>
                </a>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} <strong class="text-slate-600 font-semibold">INVENTA</strong> - Sistem Inventaris Kampus
    </footer>
</body>
</html>
