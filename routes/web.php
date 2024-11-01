<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InventoryDetailController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Halaman login sebagai halaman utama
Route::get('/', function () {
    if (Auth::check()) {
        $userRole = strtolower(Auth::user()->Role);
        if ($userRole === 'admin' || $userRole === 'super_admin') {
            return redirect()->route('admin.dashboard');
        } 
        // Dosen dan mahasiswa diarahkan ke user.dashboard
        return redirect()->route('user.dashboard');
    }
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    // Routes untuk dosen dan mahasiswa - mengarah ke views/user/dashboard.blade.php
    Route::middleware(['role:dosen,mahasiswa'])->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
        Route::get('/PeminjamanInventaris', function () {
            return view('layouts.PeminjamanInventaris');
        })->name('PeminjamanInventaris');
        Route::get('/InformasiInventaris', [ItemController::class, 'index'])->name('InformasiInventaris');
        Route::get('/inventory/detail/{id}', [InventoryDetailController::class, 'getDetail'])->name('inventory.detail');
    });

    // Routes untuk admin dan super admin - mengarah ke views/admin/dashboard.blade.php
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/AkunMahasiswa', [MahasiswaController::class, 'index'])->name('AkunMahasiswa');
        Route::post('/AkunMahasiswa', [MahasiswaController::class, 'store'])->name('AkunMahasiswa.store');
        Route::post('/AkunMahasiswa/import', [MahasiswaController::class, 'importCSV'])->name('AkunMahasiswa.import');
        Route::get('/AkunDosen', [DosenController::class, 'index'])->name('AkunDosen');
        Route::post('/AkunDosen', [DosenController::class, 'store'])->name('AkunDosen.store');
        Route::get('/InputInventaris', [ItemController::class, 'create'])->name('InputInventaris');
        Route::resource('inventaris', ItemController::class);
        Route::get('/KerusakanInventaris', function () {
            return view('layouts.KerusakanInventaris');
        })->name('KerusakanInventaris');
    });

    // Routes untuk profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
