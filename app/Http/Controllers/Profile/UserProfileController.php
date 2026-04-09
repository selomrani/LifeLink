<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        return $request->user();
    }
public function update(ProfileRequest $request)
{
    $user = $request->user();
    $user->fill($request->validated());
    if ($request->hasFile('photo')) {
        if ($user->profile_photo_path) {
            Storage::disk('s3')->delete($user->profile_photo_path);
        }
        $path = $request->file('photo')->store('avatars/profiles', 's3');
        $user->profile_photo_path = $path;
    }

    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Profile updated successfully',
        'user' => $user->fresh()
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
