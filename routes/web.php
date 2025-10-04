<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MateriController;

// Halaman menampilkan semua materi
Route::get('/', [MateriController::class, 'indexWelcom'])->name('welcome');

// Halaman profil kreator
Route::get('/profil_creator', function () {
    return view('Profil.profil');
})->name('profil.creator');

// Halaman detail untuk satu materi
Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('materi.show');
Route::get('/materi/{materi}/diskusi', [MateriController::class, 'showDiskusi'])->name('materi.diskusi');
Route::get('/materi/{materi}/kuis', [MateriController::class, 'showKuis'])->name('materi.kuis');


Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard khusus guru
    Route::get('/dashboard', [MateriController::class, 'index'])->name('dashboard');

    // Pengaturan Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- Rute Materi CRUD ---
    Route::get('/tambahmateri', [MateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');

});

require __DIR__.'/auth.php';

