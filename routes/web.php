<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KosController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

// ─── Public ───
Route::get('/', [KosController::class, 'home'])->name('home');
Route::get('/api/kos', [KosController::class, 'apiIndex'])->name('api.kos');

Route::prefix('kos')->name('kos.')->group(function () {
    // 1. Rute Public Daftar Kos (Semua orang bisa melihat hasil pencarian)
    Route::get('/', [KosController::class, 'index'])->name('index');
    
    // 2. Rute Khusus Pemilik & Admin (Create, Edit, Update, Delete)
    Route::middleware(['auth', 'role:pemilik,admin'])->group(function () {
        // Rute statis WAJIB ditaruh di atas rute dinamis (/{kos:slug})
        Route::get('/create', [KosController::class, 'create'])->name('create'); 
        Route::post('/', [KosController::class, 'store'])->name('store');
        Route::get('/{kos:slug}/edit', [KosController::class, 'edit'])->name('edit');
        Route::put('/{kos:slug}', [KosController::class, 'update'])->name('update');
        Route::delete('/{kos:slug}', [KosController::class, 'destroy'])->name('destroy');
    });

    // 3. Rute Dinamis Wajib Login (Pencari, Pemilik, Admin bisa melihat detail kos)
    Route::middleware('auth')->group(function () {
        Route::get('/{kos:slug}', [KosController::class, 'show'])->name('show');
    });
});

// ─── Guest Only (Hanya untuk yang belum login) ───
Route::middleware('guest')->group(function () {
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Auth Required (General / Semua User Login) ───
Route::middleware('auth')->group(function () {
    Route::get('/profile',          [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit',     [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/favorit',          [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorit/{kos}',   [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    
    // Rute Submit Review
    Route::post('/kos/{kos:slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// ─── Pemilik ───
Route::middleware(['auth', 'role:pemilik,admin'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard',             [PemilikDashboard::class, 'index'])->name('dashboard');
    Route::post('/kos/{kos}/toggle',     [PemilikDashboard::class, 'toggleAvailable'])->name('toggle');
});

// ─── Admin ───
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',             [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/users',                 [AdminDashboard::class, 'users'])->name('users');
    Route::put('/users/{user}/role',     [AdminDashboard::class, 'updateUserRole'])->name('users.role');
    Route::delete('/users/{user}',       [AdminDashboard::class, 'deleteUser'])->name('users.delete');
    Route::get('/kos',                   [AdminDashboard::class, 'kos'])->name('kos');
    Route::delete('/kos/{kos}',          [AdminDashboard::class, 'deleteKos'])->name('kos.delete');
});