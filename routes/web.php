<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/{id}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::put('/tags/{id}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{id}', [TagController::class, 'destroy'])->name('tags.destroy');

    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::get('/links/{id}/edit', [LinkController::class, 'edit'])->name('links.edit');
    Route::put('/links/{id}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{id}', [LinkController::class, 'destroy'])->name('links.destroy');

    Route::get('/links/trashed', [LinkController::class, 'trashed'])->name('links.trashed');
    Route::post('/links/{id}/restore', [LinkController::class, 'restore'])->name('links.restore');
    Route::delete('/links/{id}/force-delete', [LinkController::class, 'forceDelete'])->name('links.force_delete');

    Route::post('/links/{link}/share', [LinkController::class, 'share'])->name('links.share');

    Route::get('/favorites', [LinkController::class, 'favorites'])->name('links.favorites');
    Route::post('/links/{id}/favorite', [LinkController::class, 'toggleFavorite'])->name('links.toggle_favorite');

    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity_logs.index');

    Route::resource('users', \App\Http\Controllers\UserController::class)->only(['index', 'update', 'destroy']);
});
