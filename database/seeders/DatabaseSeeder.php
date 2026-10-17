<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Petugas TU',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'mahasiswa@unesa.ac.id'],
            [
                'name' => 'Farrel Kevin',
                'nim' => '23051214121',
                'study_program' => 'S1 Sistem Informasi',
                'phone_number' => '081234567890',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
            ]
        );

        $this->call([
            ItemSeeder::class,
            LoanSeeder::class,
        ]);
    }
}
