<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('login-token')->plainTextToken;
            if ($user->role->name == 'admin') {
                return response()->json([
                    'message' => 'Login successful , Welcome back admin',
                    'token' => $token,
                    'user' => $user,
                    'route' => 'admin',
                ], 200);
            }

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
                'route' => 'user',
            ], 200);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }
}
