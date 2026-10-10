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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 sm:p-10 w-full">
            <div class="text-center">
                <div class="w-10 h-10 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-600 mx-auto flex items-center justify-center mb-4 shadow-sm">
                    <x-heroicon-o-check class="w-5 h-5" />
                </div>
                <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Berhasil Dikirim!</h1>
                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Data pengajuan kamu sudah tercatat di sistem.
                </p>
            </div>

            <div class="my-6 sm:my-8 p-4 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                <div class="grid grid-cols-2 gap-3 pb-4 border-b border-slate-200">
                    <div>
                        <span class="receipt-label">Kode Transaksi</span>
                        <span class="text-sm sm:text-xl font-extrabold text-teal-700 tracking-wide block">{{ $loan->loan_code }}</span>
                    </div>
                    <div class="text-right">
                        <span class="receipt-label">Waktu Pengajuan</span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700 block">{{ $loan->requested_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                <div class="space-y-2 pb-4 border-b border-slate-200">
                    <div class="flex items-center justify-between gap-2">
                        <span class="receipt-label">Barang Dipinjam</span>
                        <span class="text-[11px] text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md font-semibold whitespace-nowrap">Dalam Antrean</span>
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
                        <span class="receipt-value">{{ $loan->borrower_name }}</span>
                        <span class="text-slate-500 text-[11px] block mt-0.5">{{ $loan->borrower_id_number }}</span>
                        <span class="text-slate-500 text-[11px] block mt-0.5">{{ $loan->study_program }}</span>
                    </div>

                    <div>
                        <span class="receipt-label">Lokasi</span>
                        <span class="receipt-value">{{ $loan->destination_building }} &mdash; {{ $loan->destination_room }}</span>
                        <span class="text-slate-500 text-[11px] block mt-0.5">WhatsApp: {{ $loan->phone_number }}</span>
                    </div>

                    <div>
                        <span class="receipt-label">Dosen Pengajar</span>
                        <span class="receipt-value">{{ $loan->lecturer_name }}</span>
                    </div>

                    <div>
                        <span class="receipt-label">Rencana Pengembalian</span>
                        <span class="receipt-value">{{ $loan->expected_return_at->format('d M Y, H:i') }} WIB</span>
                    </div>

                    <div class="col-span-2 pt-3 border-t border-slate-200">
                        <span class="receipt-label">Alasan Peminjaman</span>
                        <p class="text-sm text-slate-700 leading-relaxed mt-0.5">{{ $loan->purpose }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3 text-xs text-amber-900 mb-8">
                <x-heroicon-o-information-circle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5 text-amber-900">Langkah Pengambilan Barang</span>
                    Tunjukkan <strong>Transaksi</strong> ini ke petugas di <strong>Ruang TU</strong> dalam batas waktu <strong>30 menit</strong> dengan membawa <strong>Kartu Identitas asli</strong>.
                </div>
            </div>

            <div>
                <a href="{{ route('loans.create') }}" class="btn-primary">
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
