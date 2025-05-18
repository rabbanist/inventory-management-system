<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\TokenVerificationMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//User Authentication Routes 

Route::post('/user-registration', [AuthController::class, 'userRegistration'])->name('user.registration');
Route::post('/user-login', [AuthController::class,'userLogin'])->name('user.login');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');

Route::post('/set-password', [AuthController::class, 'resetPassword'])->name('set.password')->middleware(TokenVerificationMiddleware::class);
