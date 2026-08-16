<?php
use App\Http\Controllers\Api\AccountDashboardController;
use App\Http\Controllers\Api\AccountInfoController;
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
Route::get('products/{product}/reviews',[ProductController::class,'reviews'])->name('products.reviews');
Route::post('products/{product}/reviews',[ProductController::class,'storeReview'])->middleware('auth:sanctum')->name('submit.review');
Route::prefix('account')->middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard',[AccountDashboardController::class,'index'])->middleware('auth:sanctum')->name('account.dashboard');
    Route::get('/information',[AccountInfoController::class,'show'])->middleware('auth:sanctum')->name('account.information');
    Route::put('/information',[AccountInfoController::class,'update'])->middleware('auth:sanctum')->name('account.information.update');
    Route::patch('/information',[AccountInfoController::class,'updatePassword'])->middleware('auth:sanctum')->name('account.password.update');
    Route::patch('/avatar',[AccountInfoController::class,'updateAvatar'])->middleware('auth:sanctum')->name('account.avatar.update');
    Route::delete('/avatar',[AccountInfoController::class,'destroyAvatar'])->middleware('auth:sanctum')->name('account.avatar.destroy');
});

