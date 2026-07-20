<?php
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('categories',[CategoryController::class,'index'])->name('categories');
