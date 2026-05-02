<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ReportRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $user->load('bloodRequestPosts');
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 200);
    }
    public function update(ProfileRequest $request)
    {
        $user = $request->user();
        $user->fill($request->validated());
        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('s3')->delete($user->profile_photo_path);
            }
            $path = $request->file('photo')->store('posts_media', 's3');
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
    public function report(ReportRequest $request , User $user)
    {
        $validated = $request->validated();
        $validated['reporter_id'] = Auth::id();
        $validated['reported_user_id'] = $user->id;
        $report = Report::create($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'user was reported successfully and sent to admin for revision'
        ]);
    }
    public function userPosts(Request $request)
    {
        $user = $request->user();
        $posts = $user->bloodRequestPosts()->with('user')->get();
        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ]);
    }
}
