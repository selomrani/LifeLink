<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $user->load('role');

            if ($user->role->name === 'admin') {
                return response()->json([
                    'message' => 'Login successful, Welcome back admin',
                    'user' => $user,
                    'route' => 'admin',
                ], 200);
            }
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'route' => 'user',
            ], 200);
        }
        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }
}
