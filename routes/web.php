<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraceChatController;

Route::get('/',      fn() => view('welcome'));
Route::post('/chat', [GraceChatController::class, 'send']);
