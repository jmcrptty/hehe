<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/AkunMahasiswa', function () {
    return view('layouts.AkunMahasiswa');
})->middleware(['auth', 'verified'])->name('AkunMahasiswa');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/AkunMahasiswa', [MahasiswaController::class, 'index'])->name('AkunMahasiswa');
    Route::post('/AkunMahasiswa', [MahasiswaController::class, 'store'])->name('AkunMahasiswa.store');
    Route::post('/AkunMahasiswa/import', [MahasiswaController::class, 'importCSV'])->name('AkunMahasiswa.import');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/AkunDosen', [DosenController::class, 'index'])->name('AkunDosen');
    Route::post('/AkunDosen', [DosenController::class, 'store'])->name('AkunDosen.store');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/PeminjamanInventaris', function () {
        return view('layouts.PeminjamanInventaris');
    })->name('PeminjamanInventaris');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/InformasiInventaris', function () {
        return view('layouts.InformasiInventaris');
    })->name('InformasiInventaris');
});


Route::get('/items', [ItemController::class, 'index'])->name('items.index');;

Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
Route::post('items/store', [ItemController::class, 'store'])->name('items.store');
Route::get('items', [ItemController::class, 'index'])->name('items.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/KerusakanInventaris', function () {
        return view('layouts.KerusakanInventaris');
    })->name('KerusakanInventaris');
});
Route::get('/InputInventaris', [ItemController::class, 'create'])->name('InputInventaris');
Route::resource('inventaris', ItemController::class);

require __DIR__.'/auth.php';
