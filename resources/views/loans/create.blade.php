<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVENTA - Peminjaman Inventaris Kampus</title>
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <a href="{{ route('loans.create') }}" class="flex items-center gap-1 group">
                <img src="/images/inventa-logo.svg" alt="INVENTA Logo" class="w-10 h-10 group-hover:scale-105 transition drop-shadow-sm">
                <img src="/images/inventa-text.svg" alt="INVENTA" class="h-5 w-auto">
            </a>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-xs font-medium text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Jam Buka (08:00 - 16:00)</span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-10 flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-start">
            <div id="section-info" class="lg:col-span-5 space-y-5 lg:sticky lg:top-24 {{ (isset($errors) && $errors->any()) ? 'hidden lg:block' : 'block' }}">
                <div class="px-2">
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight leading-tight">
                        Sistem Peminjaman Inventaris Kampus
                    </h2>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Pengajuan praktis kebutuhan perlengkapan perkuliahan secara mandiri.
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 px-3 py-4 shadow-sm space-y-4">
                    <h2 class="text-xs font-bold tracking-wider text-slate-500 ml-2">Tahapan Peminjaman</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                1
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Isi Formulir secara Online</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Pilih barang dan masukkan identitas serta ruangan pemakaian.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                2
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Datang ke Ruang TU</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Sebutkan nama atau tunjukkan kode peminjaman kepada petugas.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                3
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Serahkan KTM & Ambil Barang</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Petugas memeriksa fisik barang, menyetujui, dan menyerahkan aset.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3 text-xs text-amber-900">
                    <x-heroicon-o-clock class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                    <div>
                        <span class="font-bold block mb-0.5 text-amber-900">Batas Waktu Pengambilan</span>
                        Permintaan akan kedaluwarsa otomatis dalam <strong>30 menit</strong> apabila tidak diambil di loket TU.
                    </div>
                </div>

                <!-- Navigasi form khusus Mobile -->
                <div class="pt-2 lg:hidden">
                    <button type="button" id="btn-to-form" class="w-full py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-2xl shadow-md shadow-teal-600/20 transition flex items-center justify-center gap-2 active:scale-[0.99]">
                        <span>Lanjut Isi Formulir Peminjaman</span>
                    </button>
                </div>
            </div>

            <!-- Form Pengajuan -->
            <div id="section-form" class="lg:col-span-7 {{ (isset($errors) && $errors->any()) ? 'block' : 'hidden lg:block' }}">
                <div class="mb-4 lg:hidden flex items-center justify-between">
                    <button type="button" id="btn-to-info" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-teal-700 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-sm transition active:scale-[0.98]">
                        <x-heroicon-o-arrow-left class="w-4 h-4 text-slate-500" />
                        <span>Kembali ke Informasi</span>
                    </button>
                </div>
                @if (isset($errors) && $errors->any())
                    <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs">
                        <p class="font-bold mb-1 flex items-center gap-1.5 text-rose-900">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 text-rose-600" />
                            Mohon periksa kembali formulir:
                        </p>
                        <ul class="list-disc list-inside space-y-0.5 pl-1 text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Formulir Pengajuan Peminjaman</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Lengkapi data dengan benar sesuai kartu identitas.</p>
                        </div>
                    </div>

                    <form action="{{ route('loans.store') }}" method="POST" class="p-5">
                        @csrf

                        <div class="space-y-2">
                            <label class="block text-xs font-bold tracking-wider text-slate-700">
                                Pilih Barang Inventaris <span class="text-rose-500">*</span>
                            </label>

                            <div id="item-rows-container" class="space-y-2.5">
                                @php
                                    $selectedItemIds = (array) old('item_ids', ['']);
                                    if (empty($selectedItemIds)) {
                                        $selectedItemIds = [''];
                                    }
                                @endphp

                                @foreach ($selectedItemIds as $index => $selectedId)
                                    <div class="item-row flex items-center gap-2">
                                        <div class="flex-1">
                                            <select name="item_ids[]" required class="item-select w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                                                <option value="">Pilih Barang</option>
                                                @forelse ($items as $item)
                                                    <option value="{{ $item->id }}" {{ $selectedId == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }} (Kode: {{ $item->code }})
                                                    </option>
                                                @empty
                                                    <option value="" disabled>Saat ini tidak ada barang yang tersedia</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <button type="button" class="btn-remove-item p-3 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl border border-slate-200 hover:border-rose-200 transition shrink-0 {{ $index === 0 ? 'hidden' : '' }}" title="Hapus baris barang ini">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <button type="button" id="btn-add-item-row" class="inline-flex items-center justify-center gap-1.5 text-xs font-semibold text-teal-700 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200 px-3.5 py-2.5 rounded-xl transition active:scale-[0.98] whitespace-nowrap self-start">
                                    <x-heroicon-o-plus class="w-3.5 h-3.5 shrink-0" />
                                    <span>Tambah Barang Lain</span>
                                </button>
                                <span class="text-xs text-slate-400">Bisa memilih lebih dari 1 barang.</span>
                            </div>
                        </div>

                        <div class="py-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold tracking-wider text-slate-900">
                                    Identitas Peminjam
                                </label>
                                <span class="text-xs text-slate-400">Sesuai KTM Mahasiswa</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="borrower_id_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        NIM (Nomor Induk Mahasiswa) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="borrower_id_number" id="borrower_id_number" value="{{ old('borrower_id_number') }}" required placeholder="2201010041" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                                </div>

                                <div>
                                    <label for="borrower_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Nama Lengkap <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="borrower_name" id="borrower_name" value="{{ old('borrower_name') }}" required placeholder="Nama lengkap sesuai KTM" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                                </div>

                                <div>
                                    <label for="study_program" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Program Studi <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="study_program" id="study_program" value="{{ old('study_program') }}" required placeholder="Informatika / Teknik Elektro" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                                </div>

                                <div>
                                    <label for="phone_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        No. WhatsApp Aktif <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required placeholder="081234567890" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-bold tracking-wider text-slate-900">
                                Lokasi & Durasi Pemakaian
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="destination_building" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Gedung Tujuan <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="destination_building" id="destination_building" value="{{ old('destination_building') }}" required placeholder="Gedung Kuliah Bersama (GKB)" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                                </div>

                                <div>
                                    <label for="destination_room" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Ruangan / Lab <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="destination_room" id="destination_room" value="{{ old('destination_room') }}" required placeholder="Ruang 304 / Lab Jaringan 2" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                                </div>
                            </div>

                            <div>
                                <label for="expected_return_at" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Estimasi Waktu Pengembalian <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" name="expected_return_at" id="expected_return_at" value="{{ old('expected_return_at', now()->addHours(3)->format('Y-m-d\TH:i')) }}" required class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition">
                            </div>

                            <div>
                                <label for="purpose" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Keperluan Kegiatan <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="purpose" id="purpose" rows="3" required placeholder="Presentasi tugas mata kuliah Rekayasa Perangkat Lunak" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 focus:bg-white transition leading-relaxed">{{ old('purpose') }}</textarea>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <button type="submit" class="w-full py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-2xl shadow-md shadow-teal-600/20 transition active:scale-[0.99] flex items-center justify-center gap-2">
                                <span>Ajukan Peminjaman Sekarang</span>
                            </button>
                            <p class="text-center text-xs text-slate-400 mt-3">
                                Dengan mengajukan, Anda setuju mematuhi tata tertib peminjaman aset kampus.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} <strong class="text-slate-600 font-semibold">INVENTA</strong> - Sistem Inventaris Kampus
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mobile wizard navigation
            const infoSection = document.getElementById('section-info');
            const formSection = document.getElementById('section-form');
            const btnToForm = document.getElementById('btn-to-form');
            const btnToInfo = document.getElementById('btn-to-info');

            if (btnToForm && btnToInfo) {
                btnToForm.addEventListener('click', function () {
                    infoSection.classList.add('hidden');
                    infoSection.classList.remove('block');
                    formSection.classList.remove('hidden');
                    formSection.classList.add('block');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                btnToInfo.addEventListener('click', function () {
                    formSection.classList.add('hidden');
                    formSection.classList.remove('block');
                    infoSection.classList.remove('hidden');
                    infoSection.classList.add('block');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // Dynamic multi-row dropdown repeater
            const container = document.getElementById('item-rows-container');
            const btnAddRow = document.getElementById('btn-add-item-row');

            function syncRemoveButtons() {
                if (!container) return;
                const rows = container.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    const btnRemove = row.querySelector('.btn-remove-item');
                    if (btnRemove) {
                        if (index === 0) {
                            btnRemove.classList.add('hidden');
                        } else {
                            btnRemove.classList.remove('hidden');
                        }
                    }
                });
            }

            function attachRemoveEvent(btn) {
                btn.addEventListener('click', function () {
                    const row = btn.closest('.item-row');
                    if (row) {
                        row.remove();
                        syncRemoveButtons();
                    }
                });
            }

            if (btnAddRow && container) {
                btnAddRow.addEventListener('click', function () {
                    const rows = container.querySelectorAll('.item-row');
                    if (rows.length === 0) return;
                    
                    const firstRow = rows[0];
                    const newRow = firstRow.cloneNode(true);
                    
                    // Reset select value
                    const select = newRow.querySelector('select');
                    if (select) select.selectedIndex = 0;
                    
                    // Attach remove event to new row's button
                    const btnRemove = newRow.querySelector('.btn-remove-item');
                    if (btnRemove) {
                        attachRemoveEvent(btnRemove);
                    }

                    container.appendChild(newRow);
                    syncRemoveButtons();
                });

                // Attach remove events to existing rows
                container.querySelectorAll('.btn-remove-item').forEach(attachRemoveEvent);
                syncRemoveButtons();
            }
        });
    </script>
</body>
</html>
