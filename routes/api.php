<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//User Authentication Routes 

Route::post('/user-registration', [AuthController::class, 'userRegistration'])->name('user.registration');
Route::post('/user-login', [AuthController::class,'userLogin'])->name('user.login');
