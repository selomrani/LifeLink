<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $status = Password::sendResetLink($request->only('email'));
        return response()->json(['status' => __($status), 'message'  => "Password reset link sent successfully"], 200);
    }
}
