<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;

// Home page route
Route::get('/', [HomeController::class, 'index'])->name('home');


// Auth routes
Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/login', [HomeController::class, 'login'])->name('login');

    Route::get('/registration', [HomeController::class, 'registration'])->name('registration');

    Route::get('/forgot-password', [HomeController::class, 'forgotPassword'])->name('forgot-password');

    Route::get('/send-otp', [HomeController::class, 'sendOtp'])->name('send-otp');

    Route::get('/reset-password', [HomeController::class, 'resetPassword'])->name('reset-password');

});


// Admin Dashboard route
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

Route::get('/category', [DashboardController::class, 'category'])->name('category');

Route::get('/customer', [DashboardController::class, 'customer'])->name('customer');

Route::get('/product', [DashboardController::class, 'product'])->name('product');

Route::get('/invoice', [DashboardController::class, 'invoice'])->name('invoice');

Route::get('/report', [DashboardController::class, 'report'])->name('report');

Route::get('/sale', [DashboardController::class, 'sale'])->name('sale');
