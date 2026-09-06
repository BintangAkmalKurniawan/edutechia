<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/profil-kreator', [HomeController::class, 'indexProfil'])->name('profil.creator');
Route::get('/kelas', [CourseController::class, 'catalogue'])->name('courses.index');
Route::get('/kelas/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/kelas/{course:slug}/daftar', [CourseController::class, 'enroll'])
        ->middleware('role:student')->name('courses.enroll');

    Route::middleware('role:teacher,admin')->group(function () {
        Route::get('/kelola/kelas/tambah', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/kelola/kelas', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/kelola/kelas/{course:slug}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/kelola/kelas/{course:slug}', [CourseController::class, 'update'])->name('courses.update');
        Route::patch('/kelola/kelas/{course:slug}/publikasi', [CourseController::class, 'publish'])->name('courses.publish');
        Route::delete('/kelola/kelas/{course:slug}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('/kelola/kelas/{course:slug}/materi/tambah', [MateriController::class, 'create'])->name('materi.create');
        Route::post('/kelola/kelas/{course:slug}/materi', [MateriController::class, 'store'])->name('materi.store');
        Route::get('/kelola/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
        Route::put('/kelola/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
        Route::delete('/kelola/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
        Route::get('/kelola/materi/{materi}/kuis', [QuizController::class, 'edit'])->name('quiz.edit');
        Route::put('/kelola/materi/{materi}/kuis', [QuizController::class, 'update'])->name('quiz.update');
    });

    Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('materi.show');
    Route::patch('/materi/{materi}/progres', [ProgressController::class, 'update'])->name('materi.progress');
    Route::get('/materi/{materi}/diskusi', [DiscussionController::class, 'index'])->name('materi.diskusi');
    Route::post('/materi/{materi}/diskusi', [DiscussionController::class, 'store'])->name('discussion.store');
    Route::delete('/diskusi/{post}', [DiscussionController::class, 'destroy'])->name('discussion.destroy');
    Route::get('/materi/{materi}/kuis', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/materi/{materi}/kuis', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/materi/{materi}/kuis/hasil/{attempt}', [QuizController::class, 'result'])->name('quiz.result');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/pengguna', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/guru/tambah', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/guru', [AdminUserController::class, 'store'])->name('users.store');
        Route::patch('/pengguna/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status');
    });
});

require __DIR__.'/auth.php';
