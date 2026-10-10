<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $officer = User::where('email', 'admin@admin.com')->first();
        $officerId = $officer ? $officer->id : 1;

        Item::where('code', 'INV-PRJ-002')->update([
            'status' => 'maintenance',
            'condition' => 'lightly_damaged',
            'description' => 'Proyektor LCD 3300 Lumens - Lampu redup, sedang dijadwalkan servis berkala.',
        ]);

        Item::where('code', 'INV-MIC-002')->update([
            'status' => 'damaged',
            'condition' => 'heavily_damaged',
            'description' => 'Microphone wireless handheld - Port receiver patah, tidak dapat menerima sinyal.',
        ]);

        $findItemIds = fn (array $codes) => Item::whereIn('code', $codes)->pluck('id')->toArray();

        $loanActive = Loan::create([
            'loan_code' => 'LOAN-' . date('Ymd') . '-001',
            'borrower_id_number' => '220101001',
            'borrower_name' => 'Budi Santoso',
            'study_program' => 'Sistem Informasi',
            'phone_number' => '081234567801',
            'destination_building' => 'Gedung F',
            'destination_room' => 'Lab Komputer 1',
            'purpose' => 'Praktikum mata kuliah Pemrograman Web Terdistribusi.',
            'lecturer_name' => 'Dr. Eng. Ir. Budi Santoso, M.Kom',
            'requested_at' => now()->subHours(2),
            'expected_return_at' => now()->addHours(2), // Masih aman, belum terlambat
            'approved_at' => now()->subHours(2),
            'status' => 'borrowed',
            'officer_id' => $officerId,
        ]);
        $activeItemIds = $findItemIds(['INV-PRJ-001', 'INV-KBL-001']);
        $loanActive->items()->sync($activeItemIds);
        Item::whereIn('id', $activeItemIds)->update(['status' => 'borrowed']);

        $loanOverdue = Loan::create([
            'loan_code' => 'LOAN-' . date('Ymd') . '-002',
            'borrower_id_number' => '220101002',
            'borrower_name' => 'Siti Rahmawati',
            'study_program' => 'Informatika',
            'phone_number' => '085234567802',
            'destination_building' => 'Gedung D',
            'destination_room' => 'Ruang Teater 201',
            'purpose' => 'Seminar Himpunan Mahasiswa Informatika (HIMAIF).',
            'lecturer_name' => 'Prof. Dr. Ahmad Dahlan, M.T.',
            'requested_at' => now()->subHours(4),
            'expected_return_at' => now()->subHours(1), // Batas waktu sudah lewat 1 jam lalu!
            'approved_at' => now()->subHours(4),
            'status' => 'borrowed',
            'officer_id' => $officerId,
        ]);
        $overdueItemIds = $findItemIds(['INV-MIC-001', 'INV-SPK-001']);
        $loanOverdue->items()->sync($overdueItemIds);
        Item::whereIn('id', $overdueItemIds)->update(['status' => 'borrowed']);

        $loanPending1 = Loan::create([
            'loan_code' => 'LOAN-' . date('Ymd') . '-003',
            'borrower_id_number' => '220101003',
            'borrower_name' => 'Dimas Arya Pratama',
            'study_program' => 'Sistem Informasi',
            'phone_number' => '087734567803',
            'destination_building' => 'Gedung E',
            'destination_room' => 'Ruang Kelas 304',
            'purpose' => 'Presentasi Tugas Akhir / Capstone Project kelompok 4.',
            'lecturer_name' => 'Siti Nurhaliza, S.Kom., M.Cs.',
            'requested_at' => now()->subMinutes(15),
            'expected_return_at' => now()->addHours(3),
            'status' => 'pending',
        ]);
        $loanPending1->items()->sync($findItemIds(['INV-LPT-001']));

        $loanPending2 = Loan::create([
            'loan_code' => 'LOAN-' . date('Ymd') . '-004',
            'borrower_id_number' => '220101004',
            'borrower_name' => 'Anisa Putri',
            'study_program' => 'Bisnis Digital',
            'phone_number' => '081334567804',
            'destination_building' => 'Gedung Rektorat',
            'destination_room' => 'Ruang Sidang Utama',
            'purpose' => 'Lomba Business Plan tingkat nasional.',
            'lecturer_name' => 'Bambang Sudarsono, M.M.',
            'requested_at' => now()->subMinutes(5),
            'expected_return_at' => now()->addHours(4),
            'status' => 'pending',
        ]);
        $loanPending2->items()->sync($findItemIds(['INV-PTR-001', 'INV-EXT-001']));

        $loanReturned1 = Loan::create([
            'loan_code' => 'LOAN-' . now()->subDay()->format('Ymd') . '-005',
            'borrower_id_number' => '220101005',
            'borrower_name' => 'Rian Hidayat',
            'study_program' => 'Teknik Komputer',
            'phone_number' => '081934567805',
            'destination_building' => 'Gedung F',
            'destination_room' => 'Lab Jaringan',
            'purpose' => 'Ujian sertifikasi jaringan komputer CCNA.',
            'lecturer_name' => 'Hendra Setiawan, S.T., M.Kom.',
            'requested_at' => now()->subDay()->setTime(9, 0),
            'expected_return_at' => now()->subDay()->setTime(12, 0),
            'approved_at' => now()->subDay()->setTime(9, 10),
            'returned_at' => now()->subDay()->setTime(11, 45),
            'status' => 'returned',
            'return_condition' => 'good',
            'officer_notes' => 'Barang kembali lengkap dan berfungsi dengan sangat baik.',
            'officer_id' => $officerId,
        ]);
        $loanReturned1->items()->sync($findItemIds(['INV-CAM-001', 'INV-ADT-001']));

        $loanReturned2 = Loan::create([
            'loan_code' => 'LOAN-' . now()->subDays(2)->format('Ymd') . '-006',
            'borrower_id_number' => '220101006',
            'borrower_name' => 'Farah Nabila',
            'study_program' => 'Sistem Informasi',
            'phone_number' => '082134567806',
            'destination_building' => 'Gedung E',
            'destination_room' => 'Ruang 202',
            'purpose' => 'Kuliah tamu dengan praktisi industri.',
            'lecturer_name' => 'Dra. Endang Sulistiyowati, M.Pd.',
            'requested_at' => now()->subDays(2)->setTime(13, 0),
            'expected_return_at' => now()->subDays(2)->setTime(16, 0),
            'approved_at' => now()->subDays(2)->setTime(13, 5),
            'returned_at' => now()->subDays(2)->setTime(15, 50),
            'status' => 'returned',
            'return_condition' => 'good',
            'officer_notes' => 'Pengembalian tepat waktu sebelum loket tutup.',
            'officer_id' => $officerId,
        ]);
        $loanReturned2->items()->sync($findItemIds(['INV-PRJ-003']));

        $loanReturned3 = Loan::create([
            'loan_code' => 'LOAN-' . now()->subDays(3)->format('Ymd') . '-007',
            'borrower_id_number' => '220101007',
            'borrower_name' => 'Eko Prasetyo',
            'study_program' => 'Informatika',
            'phone_number' => '085734567807',
            'destination_building' => 'Gedung D',
            'destination_room' => 'Lab Multimedia',
            'purpose' => 'Render video tugas akhir animasi.',
            'lecturer_name' => 'Agus Priyono, M.Kom.',
            'requested_at' => now()->subDays(3)->setTime(10, 0),
            'expected_return_at' => now()->subDays(3)->setTime(14, 0),
            'approved_at' => now()->subDays(3)->setTime(10, 15),
            'returned_at' => now()->subDays(3)->setTime(14, 10),
            'status' => 'returned',
            'return_condition' => 'good',
            'officer_notes' => 'Selesai pemakaian dengan baik.',
            'officer_id' => $officerId,
        ]);
        $loanReturned3->items()->sync($findItemIds(['INV-KBL-002']));
    }
}
