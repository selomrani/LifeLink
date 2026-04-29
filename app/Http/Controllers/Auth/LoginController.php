<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return response()->json([
                    'message' => 'Your account has been banned.',
                ], 403);
            }

            $user->load('role');
            $token = $user->createToken('access_token')->plainTextToken;
            $isAdmin = $user->role->name === 'admin';

            return response()->json([
                'message' => $isAdmin ? 'Welcome back admin' : 'Login successful',
                'user' => $user,
                'token' => $token,
                'route' => $isAdmin ? 'admin' : 'user',
            ], 200);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }
}
