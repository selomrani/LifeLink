<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Donations\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::view('register', 'Auth.register');
Route::view('login', 'Auth.login')->name('login');
Route::post('/register', RegisterController::class)->name('register');
Route::view('/dashboard', 'home.dashboard')->name('user.dashboard');
Route::post('/login', LoginController::class)->name('login.attempt');
Route::get('/donate', [StripeController::class, 'index'])->name('donations.index');
Route::post('/donate/checkout', [StripeController::class, 'checkout'])->name('donations.checkout');
Route::get('/donate/success', fn() => 'Payment successful!')->name('donations.success');
Route::get('/donate/cancel',  fn() => 'Payment cancelled.')->name('donations.cancel');
