<?php
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('categories',[CategoryController::class,'index'])->name('categories');
Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('/otp/request','otpRequest')->name('otp.request');
    Route::post('/login/otp','verifyOtp')->name('otp.verify');
    Route::post('/login/password','verifyPassword')->name('password.verify');
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum')->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum')->name('auth.user');
});
Route::apiResource('products',ProductController::class);

