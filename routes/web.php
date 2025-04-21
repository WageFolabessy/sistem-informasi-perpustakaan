<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\SiteUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;


// Rute Autentikasi Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // --- Manajemen Buku ---
        Route::resource('categories', CategoryController::class);
        Route::resource('authors', AuthorController::class);
        Route::resource('publishers', PublisherController::class);
        Route::resource('books', BookController::class);
        Route::post('books/{book}/copies', [BookController::class, 'storeCopy'])->name('books.copies.store');
        Route::delete('book-copies/{copy}', [BookController::class, 'destroyCopy'])->name('book-copies.destroy');
        Route::put('book-copies/{copy}', [BookController::class, 'updateCopy'])->name('book-copies.update');

        // --- Manajemen Siswa ---
        Route::get('site-users/pending', [SiteUserController::class, 'pendingRegistrations'])->name('site-users.pending');
        Route::patch('site-users/{siteUser}/activate', [SiteUserController::class, 'activate'])->name('site-users.activate');
        Route::delete('site-users/{siteUser}/reject', [SiteUserController::class, 'reject'])->name('site-users.reject');
        Route::resource('site-users', SiteUserController::class);
    });
});
