<?php
use App\Http\Controllers\Api\AccountDashboardController;
use App\Http\Controllers\Api\AccountInfoController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\WishlistController;
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
Route::get('products/{product}/reviews',[ProductReviewController::class,'index'])->name('reviews');
Route::post('products/{product}/reviews',[ProductReviewController::class,'store'])->middleware('auth:sanctum')->name('reviews.store');
Route::post('reviews/{review}/vote',[ProductReviewController::class,'reviewVote'])->middleware('auth:sanctum')->name('review.vote');
Route::prefix('account')->middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard',[AccountDashboardController::class,'index'])->middleware('auth:sanctum')->name('account.dashboard');
    Route::get('/information',[AccountInfoController::class,'show'])->middleware('auth:sanctum')->name('account.information');
    Route::put('/information',[AccountInfoController::class,'update'])->middleware(['auth:sanctum','throttle:2,1'])->name('account.information.update');
    Route::patch('/information',[AccountInfoController::class,'updatePassword'])->middleware('auth:sanctum')->name('account.password.update');
    Route::patch('/avatar',[AccountInfoController::class,'updateAvatar'])->middleware('auth:sanctum')->name('account.avatar.update');
    Route::delete('/avatar',[AccountInfoController::class,'destroyAvatar'])->middleware('auth:sanctum')->name('account.avatar.destroy');
    Route::get('/orders',[OrderController::class,'index'])->middleware('auth:sanctum')->name('account.orders');
    Route::get('/notifications',[NotificationController::class,'index'])->middleware('auth:sanctum')->name('account.notifications');
    Route::delete('/notifications/{id}',[NotificationController::class,'destroy'])->middleware('auth:sanctum')->name('account.notifications.destroy');
    Route::patch('/notifications/{id}/read',[NotificationController::class,'markAsRead'])->middleware('auth:sanctum')->name('account.notifications.read');
    Route::patch('/notifications/read',[NotificationController::class,'markAsReadAll'])->middleware('auth:sanctum')->name('account.notifications.readAll');
    Route::get('/addresses',[AddressController::class,'index'])->middleware('auth:sanctum')->name('account.addresses');
    Route::post('/addresses',[AddressController::class,'store'])->middleware('auth:sanctum')->name('account.addresses.store');
    Route::put('/addresses/{address}',[AddressController::class,'update'])->middleware('auth:sanctum')->name('account.addresses.update');
    Route::delete('/addresses/{address}',[AddressController::class,'destroy'])->middleware('auth:sanctum')->name('account.addresses.delete');
    Route::patch('/addresses/{address}/default',[AddressController::class,'setDefault'])->middleware('auth:sanctum')->name('account.addresses.default');
});
Route::prefix('cart')
    ->controller(CartController::class)
    ->middleware('auth:sanctum')->group(function(){
    Route::get('/','index')->name('cart');
    Route::post('/','store')->name('cart.store');
    Route::patch('{id}','update')->name('cart.update');
    Route::delete('clear','clear')->name('cart.clear');
    Route::delete('{id}','destroy')->name('cart.destroy');
    Route::post('merge','merge')->name('cart.merge');
});
Route::get('wishlist',[WishlistController::class,'index'])->middleware('auth:sanctum')->name('wishlist');
Route::post('wishlist/item/{id}',[WishlistController::class,'store'])->middleware('auth:sanctum')->name('wishlist.store');
Route::delete('wishlist/item/{id}',[WishlistController::class,'destroy'])->middleware('auth:sanctum')->name('wishlist.delete');

