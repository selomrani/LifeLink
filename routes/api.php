<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Profile\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/profile',[UserProfileController::class,'show']);
});
Route::get('/test', function () {
    return response()->json(['message' => 'LifeLink API is live!']);
});
Route::post('/forgot-password', ForgotPasswordController::class);
Route::post('/reset-password', ResetPasswordController::class);
