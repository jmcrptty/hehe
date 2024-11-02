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
    // Routes untuk dosen dan mahasiswa
    Route::middleware(['role:dosen,mahasiswa'])->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
        Route::get('/PeminjamanInventaris', function () {
            return view('layouts.PeminjamanInventaris');
        })->name('PeminjamanInventaris');
        Route::get('/InformasiInventaris', [ItemController::class, 'index'])->name('InformasiInventaris');
        Route::get('/inventory/detail/{id}', [InventoryDetailController::class, 'getDetail'])->name('inventory.detail');
    });

    // Routes untuk admin dan super admin
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Grup route untuk manajemen mahasiswa
        Route::group(['prefix' => 'AkunMahasiswa', 'as' => 'AkunMahasiswa.'], function () {
            Route::get('/', [MahasiswaController::class, 'index'])->name('index');
            Route::post('/store', [MahasiswaController::class, 'store'])->name('store');
            Route::post('/import', [MahasiswaController::class, 'importCSV'])->name('import');
        });

        // Grup route untuk manajemen dosen
        Route::group(['prefix' => 'AkunDosen', 'as' => 'AkunDosen.'], function () {
            Route::get('/', [DosenController::class, 'index'])->name('index');
            Route::post('/store', [DosenController::class, 'store'])->name('store');
            Route::post('/import', [DosenController::class, 'importCSV'])->name('import');
        });
        
        // Route untuk inventaris
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
