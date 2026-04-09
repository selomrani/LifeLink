<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        return $request->user();
    }
    public function update(ProfileRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user
        ], 200);
    }
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Profile deleted successfully',
        ], 200);
    }
}
