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
        // validate the request (done automatically by LoginRequest)
        if (Auth::attempt($request->only('email', 'password'))) {
            
            // DELETE THIS: $request->session()->regenerate(); <--- This causes the error

            $user = Auth::user();
            $user->load('role');

            // Create a fresh token for this user
            $token = $user->createToken('access_token')->plainTextToken;

            $isAdmin = $user->role->name === 'admin';

            return response()->json([
                'message' => $isAdmin ? 'Welcome back admin' : 'Login successful',
                'user' => $user,
                'token' => $token, // The frontend must save this token
                'route' => $isAdmin ? 'admin' : 'user',
            ], 200);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }
}