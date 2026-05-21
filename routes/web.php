<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('pasien', PasienController::class);
        Route::resource('dokter', DokterController::class);
        Route::resource('obat', ObatController::class);
        
        // Halaman Laporan Penjualan (Hanya Admin/Manajemen)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    Route::middleware(['role:admin,dokter'])->group(function () {
        Route::resource('resep', ResepController::class);
    });

    Route::middleware(['role:pasien'])->group(function () {
        Route::get('/riwayat-kesehatan', [ResepController::class, 'riwayatPasien'])->name('pasien.riwayat');
    });

});