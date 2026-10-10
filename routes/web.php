<?php
 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicLoanController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Halaman Utama / Landing & Informasi Peminjaman
Route::get('/', [PublicLoanController::class, 'home'])->name('home');

// Autentikasi Peminjam (Mahasiswa)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Formulir & Proses Peminjaman (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/loans/create', [PublicLoanController::class, 'index'])->name('loans.create');
    Route::post('/loans', [PublicLoanController::class, 'store'])->name('loans.store')->middleware('throttle:10,1');
});

// Halaman Sukses Bukti Peminjaman
Route::get('/loans/success/{code}', [PublicLoanController::class, 'success'])->name('loans.success');

// Jalur Eksekusi Artisan di Server Produksi (FastPanel tanpa akses terminal SSH)
Route::get('/artisan/migrate-fresh', function () {
    Artisan::call('migrate:fresh', [
        '--force' => true,
        '--seed' => true,
    ]);

    return '<pre style="background:#0f172a;color:#38bdf8;padding:20px;font-family:monospace;border-radius:8px;">'
        . "=== FASTPANEL ARTISAN EXECUTION (migrate:fresh --seed) ===\n\n"
        . htmlspecialchars(Artisan::output())
        . '</pre>';
});

Route::get('/artisan/migrate', function () {
    Artisan::call('migrate', [
        '--force' => true,
    ]);

    return '<pre style="background:#0f172a;color:#38bdf8;padding:20px;font-family:monospace;border-radius:8px;">'
        . "=== FASTPANEL ARTISAN EXECUTION (migrate) ===\n\n"
        . htmlspecialchars(Artisan::output())
        . '</pre>';
});

