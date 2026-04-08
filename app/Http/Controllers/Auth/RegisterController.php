<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\BloodType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $role = Role::where('name', $request->role)->firstOrFail();
        $bloodType = BloodType::where('name', $request->blood_type)->firstOrFail();

        $user = User::create([
            'firstname'     => $validated['firstname'],
            'lastname'      => $validated['lastname'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'role_id'       => $role->id,
            'blood_type_id' => $bloodType->id,
        ]);
        Auth::login($user);
        return response()->json([
            'status'  => 'success',
            'message' => 'Registration successful',
            'user'    => $user->load(['bloodType', 'role']),
        ], 201);
    }
}
