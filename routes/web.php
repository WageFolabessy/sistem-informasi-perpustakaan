<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SiteUserController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LostReportController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookController as UserBookController;


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

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // --- Manajemen Buku ---
        Route::resource('categories', CategoryController::class);
        Route::resource('authors', AuthorController::class);
        Route::resource('publishers', PublisherController::class);
        Route::resource('books', AdminBookController::class);
        Route::post('books/{book}/copies', [AdminBookController::class, 'storeCopy'])->name('books.copies.store');
        Route::delete('book-copies/{copy}', [AdminBookController::class, 'destroyCopy'])->name('book-copies.destroy');
        Route::put('book-copies/{copy}', [AdminBookController::class, 'updateCopy'])->name('book-copies.update');

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
            Route::get('procurements', [ReportController::class, 'procurementReport'])->name('procurements');
            Route::get('procurements/export', [ReportController::class, 'exportProcurementsExcel'])->name('procurements.export');
            Route::get('lost-books', [ReportController::class, 'lostBookReport'])->name('lost-books');
            Route::get('lost-books/export', [ReportController::class, 'exportLostBooksExcel'])->name('lost-books.export');
            Route::get('fines', [ReportController::class, 'fineReport'])->name('fines');
            Route::get('fines/export', [ReportController::class, 'exportFinesExcel'])->name('fines.export');
        });

        // --- Pengaturan Sistem ---
        Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

// --- Rute Autentikasi Siswa ---
Route::middleware('guest:web')->group(function () {
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Register
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('/register/pending', function () {
        return view('user.auth.pending');
    })->name('register.pending');
});

// --- Rute Siswa Terautentikasi ---
Route::middleware('auth:web')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/katalog', [UserBookController::class, 'index'])->name('catalog.index');
    Route::get('/katalog/search', [UserBookController::class, 'searchApi'])->name('catalog.search.api');
    Route::get('/katalog/{book:slug}', [UserBookController::class, 'show'])->name('catalog.show');
});
