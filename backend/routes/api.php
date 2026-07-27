<?php
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('categories',[CategoryController::class,'index'])->name('categories');
Route::post('auth/otp/request',[AuthController::class,'otpRequest'])->name('otp.request');
Route::post('auth/otp/verify',[AuthController::class,'verifyOtp'])->name('otp.verify');
Route::post('auth/password/verify',[AuthController::class,'verifyPassword'])->name('password.verify');
Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->post('logout',[AuthController::class,'logout'])->name('logout');
