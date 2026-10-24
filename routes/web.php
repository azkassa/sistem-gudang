<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\SupplyController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.proses');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    // Hak akses (tambah/lihat/ubah/hapus) dicek di CrudController, per role.
    $modul = [
        'supply' => SupplyController::class,
        'absensi' => AbsensiController::class,
        'barang' => BarangController::class,
        'karyawan' => KaryawanController::class,
        'pengguna' => PenggunaController::class,
    ];

    foreach ($modul as $uri => $controller) {
        Route::resource($uri, $controller)->except('show')->parameters([$uri => 'id']);
    }
});
