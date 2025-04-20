<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes-notifikasi', function () {
    return view('notif'); // Nama view tanpa .blade.php
});

Route::post('/store-fcm-token', [FcmController::class, 'storeToken']);

Route::get('/kirim-tes-notif', [NotificationController::class, 'sendTestNotification']);