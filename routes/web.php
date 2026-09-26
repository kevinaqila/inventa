<?php

use App\Http\Controllers\PublicLoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicLoanController::class, 'index'])->name('loans.create');
Route::post('/loans', [PublicLoanController::class, 'store'])->name('loans.store')->middleware('throttle:10,1');
Route::get('/loans/success/{code}', [PublicLoanController::class, 'success'])->name('loans.success');
