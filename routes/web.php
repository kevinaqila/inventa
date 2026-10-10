<?php
 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicLoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicLoanController::class, 'home'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/loans/create', [PublicLoanController::class, 'index'])->name('loans.create');
    Route::post('/loans', [PublicLoanController::class, 'store'])->name('loans.store')->middleware('throttle:10,1');
});

Route::get('/loans/success/{code}', [PublicLoanController::class, 'success'])->name('loans.success');


