<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SiteUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LostReportController;

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

        // --- Manajemen Admin ---
        Route::resource('admin-users', AdminUserController::class)->except(['show']);

        // --- Profil Admin ---
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

        // --- Manajemen Sirkulasi ---
        Route::get('borrowings/overdue', [BorrowingController::class, 'overdueIndex'])->name('borrowings.overdue');
        Route::resource('borrowings', BorrowingController::class)->only([
            'index',
            'create',
            'store',
            'show',
            'destroy'
        ]);
        Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'processReturn'])->name('borrowings.return');

        // --- Manajemen Booking ---
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('bookings/{booking}/convert', [BookingController::class, 'convert'])->name('bookings.convert');

        // --- Manajemen Denda ---
        Route::get('fines', [FineController::class, 'index'])->name('fines.index');
        Route::get('fines/{fine}', [FineController::class, 'show'])->name('fines.show');
        Route::patch('fines/{fine}/pay', [FineController::class, 'pay'])->name('fines.pay');
        Route::patch('fines/{fine}/waive', [FineController::class, 'waive'])->name('fines.waive');

        // --- Laporan Kehilangan ---
        Route::get('lost-reports', [LostReportController::class, 'index'])->name('lost-reports.index');
        Route::get('lost-reports/{lost_report}', [LostReportController::class, 'show'])->name('lost-reports.show');
        Route::patch('lost-reports/{lost_report}/verify', [LostReportController::class, 'verify'])->name('lost-reports.verify');
        Route::patch('lost-reports/{lost_report}/resolve', [LostReportController::class, 'resolve'])->name('lost-reports.resolve');

        // --- Laporan ---
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('borrowings', [ReportController::class, 'borrowingReport'])->name('borrowings');
            Route::get('borrowings/export', [ReportController::class, 'exportBorrowingsExcel'])->name('borrowings.export');
            // Route::get('fines', [ReportController::class, 'fineReport'])->name('fines'); // Untuk Lap. Denda nanti
            // Route::get('lost-books', [ReportController::class, 'lostBookReport'])->name('lost-books'); // Untuk Lap. Kehilangan nanti
            // Route::get('procurements', [ReportController::class, 'procurementReport'])->name('procurements'); // Untuk Lap. Pengadaan nanti
        });

        // --- Pengaturan Sistem ---
        Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
