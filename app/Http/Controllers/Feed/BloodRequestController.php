<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Requests\BloodPostRequest;
use App\Models\BloodREquestPost;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function index()
    {
        $allbloodrequests = BloodREquestPost::with('comments')->with('user')->get();

        return response()->json([
            'status' => 'success',
            'data' => $allbloodrequests,
            'message' => 'All Blood Requests',
        ]);
    }

    public function show(BloodREquestPost $bloodrequest)
    {
        return response()->json([
            'status' => 'success',
            'data' => $bloodrequest,
            'message' => 'Blood Request',
            'comments' => $bloodrequest->comments,
        ]);
    }

    public function store(BloodREquestPost $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $bloodrequest = BloodREquestPost::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $bloodrequest,
            'message' => 'Blood Request created successfully',
        ]);
    }

    public function destroy(BloodREquestPost $bloodrequest)
    {
        $bloodrequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blood Request deleted successfully',
        ]);
    }

    public function update(BloodREquestPost $bloodrequest, BloodPostRequest $request)
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
