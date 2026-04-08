<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming password reset request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();
            }
        );

        // Check if the reset was successful based on Laravel's Password Broker status
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => __($status),
                'message' => "Password reset successfully."
            ], 200);
        }

        // Return a 400 error if the token is invalid/expired
        return response()->json([
            'status' => __($status),
            'message' => "The password reset failed."
        ], 400);
    }
}