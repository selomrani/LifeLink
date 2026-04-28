<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Requests\BloodPostRequest;
use App\Http\Requests\FeedRequest;
use App\Models\BloodRequestPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $isCompatibleOnly = $request->boolean('compatible');

        if ($isCompatibleOnly) {
            $canGiveTo = $user->bloodType->can_give_to;

            $posts = BloodRequestPost::whereIn('blood_type', $canGiveTo)
                ->with(['user', 'comments'])
                ->latest()
                ->get();

            return response()->json([
                'message' => 'Showing compatible posts only',
                'data' => $posts
            ]);
        } else {

            $posts = BloodRequestPost::with(['user', 'comments'])
                ->latest()
                ->get();
            return response()->json([
                'message' => 'Showing all posts',
                'data' => $posts
            ]);
        }
    }
    public function store(BloodRequestPost $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $bloodrequest = BloodRequestPost::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $bloodrequest,
            'message' => 'Blood Request created successfully',
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
}
