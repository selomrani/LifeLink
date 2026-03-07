<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::view('register', 'Auth.register');
Route::view('login', 'Auth.login')->name('login');
Route::post('/register', RegisterController::class)->name('register');
Route::view('/dashboard', 'home.dashboard')->name('user.dashboard');
Route::post('/login', LoginController::class)->name('login.attempt');
