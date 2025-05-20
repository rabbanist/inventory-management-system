<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\TokenVerificationMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//User Authentication Routes

Route::post('/user-registration', [AuthController::class, 'userRegistration'])->name('user.registration');
Route::post('/user-login', [AuthController::class, 'userLogin'])->name('user.login');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password')->middleware('authenticated');

Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');



// Category Routes
Route::middleware('authenticated')->group(function () {

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category-list', 'categoryList')->name('category.list');
        Route::post('/category-create', 'createCategory')->name('category.store');
        Route::get('/category/{id}', 'categoryById')->name('category.show');
        Route::post('/category-update/{id}', 'updateCategory')->name('category.update');
        Route::post('/category-delete/{id}', 'deleteCategory')->name('category.delete');
    });

    // Customer Routes
    Route::apiResource('customer', CustomerController::class);
});


