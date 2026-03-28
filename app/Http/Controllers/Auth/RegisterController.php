<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\BloodType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $role = Role::where('name', $request->role)->firstOrFail();
        $bloodType = BloodType::where('name', $request->blood_type)->firstOrFail();
        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => $role->id,
            'blood_type_id' => $bloodType->id,
        ]);

        $token = $user->createToken('lifelink-token')->plainTextToken; //

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user->load(['bloodType', 'role']),
        ], 201);
    }
}
