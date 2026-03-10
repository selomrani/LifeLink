<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
{
    $validated = $request->validate([
        'firstname' => 'required|string|max:50',
        'lastname'  => 'required|string|max:50',
        'email'     => 'required|email|max:255|unique:users,email',
        'password'  => 'required|string|min:8|confirmed',
    ]);
    $validated['password'] = bcrypt($validated['password']);
    $validated['role_id'] = 1;
    $user = User::create($validated);
    Auth::login($user);
    return redirect()->route('dashboard');
}
}
