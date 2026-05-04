<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Requests\BloodPostRequest;
use App\Http\Requests\FeedRequest;
use App\Models\BloodRequestPost;
use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $isCompatibleOnly = $request->boolean('compatible');

        if ($isCompatibleOnly) {
            $canGiveTo = $user->bloodType->can_give_to;

            $posts = BloodRequestPost::where('status', '!=', 'completed')
                ->whereIn('blood_type', $canGiveTo)
                ->with(['user', 'comments'])
                ->latest()
                ->get();

            return response()->json([
                'message' => 'Showing compatible posts only',
                'data' => $posts
            ]);
        } else {

            $posts = BloodRequestPost::where('status', '!=', 'completed')
                ->with(['user', 'comments'])
                ->latest()
                ->get();
            return response()->json([
                'message' => 'Showing all posts',
                'data' => $posts
            ]);
        }
    }
    public function store(BloodPostRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $bloodtype = BloodType::find($validated['blood_type_id']);
        $validated['blood_type'] = $bloodtype->name;

        if ($request->hasFile('media_path')) {
            $path = $request->file('media_path')->store('posts_media', 's3');
            $validated['media_path'] = Storage::disk('s3')->url($path);
        }

        $bloodrequest = BloodRequestPost::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $bloodrequest,
            'message' => 'Blood Request created successfully',
        ]);
    }
    public function show(BloodRequestPost $bloodrequest)
    {
        $bloodrequest->load(['user', 'comments.user']);

        return response()->json([
            'status' => 'success',
            'data' => $bloodrequest,
        ]);
    }

    public function destroy(BloodRequestPost $bloodrequest)
    {
        $bloodrequest->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Blood Request deleted successfully',
        ]);
    }

    public function update(BloodRequestPost $bloodrequest, BloodPostRequest $request)
    {
        $validated = $request->validated();

        $bloodrequest->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $bloodrequest,
            'message' => 'Blood Request updated successfully',
        ]);
    }

    public function close(BloodRequestPost $bloodrequest)
    {
        $bloodrequest->update(['status' => 'completed']);

        return response()->json([
            'status' => 'success',
            'data' => $bloodrequest,
            'message' => 'Blood Request marked as completed',
        ]);
    }
}
