@extends('layouts.app')

@section('title', 'INVENTA - Formulir Peminjaman Inventaris')

@section('content')
<div class="max-w-6xl xl:max-w-7xl mx-auto w-full lg:px-8 sm:py-6 flex-1">
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

    <div class="bg-white sm:rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <form action="{{ route('loans.store') }}" method="POST" class="p-6 sm:p-8 lg:p-6">
            @csrf
            <input type="hidden" name="phone_number" value="{{ auth()->user()->phone_number }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
                <div class="space-y-6">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/90 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                            <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Data Peminjam</span>
                        </div>

                        <div class="grid grid-cols-12 gap-x-4 gap-y-3 text-xs">
                            <div class="col-span-7 sm:col-span-8">
                                <span class="text-[11px] text-slate-400 block font-medium">Nama Mahasiswa</span>
                                <span class="font-bold text-slate-900 text-xs sm:text-sm block mt-0.5 break-words">{{ auth()->user()->name }}</span>
                            </div>

                            <div class="col-span-5 sm:col-span-4">
                                <span class="text-[11px] text-slate-400 block font-medium">NIM</span>
                                <span class="font-semibold text-slate-800 text-xs sm:text-sm block mt-0.5">{{ auth()->user()->nim }}</span>
                            </div>

                            <div class="col-span-7 sm:col-span-8">
                                <span class="text-[11px] text-slate-400 block font-medium">Program Studi</span>
                                <span class="font-semibold text-slate-800 text-xs sm:text-sm block mt-0.5 break-words">{{ auth()->user()->study_program ?? '-' }}</span>
                            </div>

                            <div class="col-span-5 sm:col-span-4">
                                <span class="text-[11px] text-slate-400 block font-medium">No. WhatsApp</span>
                                <span class="font-semibold text-slate-800 text-xs sm:text-sm block mt-0.5">{{ auth()->user()->phone_number ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2.5">
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
                                        <select name="item_ids[]" required class="item-select form-input py-3">
                                            <option value="">Pilih Barang</option>
                                            @forelse ($items as $item)
                                                <option value="{{ $item->id }}" {{ $selectedId == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
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

                        <div class="pt-1">
                            <button type="button" id="btn-add-item-row" class="inline-flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3.5 py-2.5 rounded-xl transition active:scale-[0.98] whitespace-nowrap">
                                <x-heroicon-o-plus class="w-3.5 h-3.5 shrink-0" />
                                <span>Tambah Barang Lain</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 lg:border-l lg:border-slate-100 lg:pl-10">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="destination_building" class="form-label">
                                Gedung Tujuan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="destination_building" id="destination_building" value="{{ old('destination_building') }}" required placeholder="Gedung Kuliah Bersama (GKB)" class="form-input">
                        </div>

                        <div>
                            <label for="destination_room" class="form-label">
                                Ruangan / Lab <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="destination_room" id="destination_room" value="{{ old('destination_room') }}" required placeholder="Ruang 304 / Lab Jaringan 2" class="form-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="lecturer_name" class="form-label">
                                Dosen Pengajar <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="lecturer_name" id="lecturer_name" value="{{ old('lecturer_name') }}" required class="form-input">
                        </div>

                        <div>
                            <label for="expected_return_at" class="form-label">
                                Estimasi Waktu Pengembalian <span class="text-rose-500">*</span>
                            </label>
                            <input type="datetime-local" name="expected_return_at" id="expected_return_at" value="{{ old('expected_return_at', now()->addHours(3)->format('Y-m-d\TH:i')) }}" required class="form-input">
                        </div>
                    </div>

                    <div>
                        <label for="purpose" class="form-label">
                            Alasan peminjaman <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="purpose" id="purpose" rows="3" required placeholder="Presentasi tugas mata kuliah Rekayasa Perangkat Lunak" class="form-input leading-relaxed">{{ old('purpose') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn-primary w-full py-3.5 text-sm font-bold shadow-md shadow-teal-700/10">
                            <span>Ajukan Peminjaman Sekarang</span>
                        </button>
                        <p class="text-center text-xs text-slate-400 my-2.5">
                            Dengan mengajukan, Anda setuju mematuhi tata tertib peminjaman aset kampus.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                
                const select = newRow.querySelector('select');
                if (select) select.selectedIndex = 0;
                
                const btnRemove = newRow.querySelector('.btn-remove-item');
                if (btnRemove) {
                    attachRemoveEvent(btnRemove);
                }

                container.appendChild(newRow);
                syncRemoveButtons();
            });

            container.querySelectorAll('.btn-remove-item').forEach(attachRemoveEvent);
            syncRemoveButtons();
        }
    });
</script>
@endpush
