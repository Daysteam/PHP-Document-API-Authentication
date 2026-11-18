<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:1,1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});
Route::post('register', [AuthController::class,'register']);

Route::middleware('auth:api')->group(function(){
    Route::apiResource('siswas',SiswaController::class)->middleware('role:admin');
    Route::post('logout', [AuthController::class,'logout']);
    Route::get('profile', [AuthController::class,'profile']);
});
