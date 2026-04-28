<?php

namespace App\Http\Controllers\Feed; // Fixed: Added \Feed

use App\Http\Controllers\Controller; // Added: Required to extend the base Controller
use App\Http\Requests\BloodPostRequest;
use App\Http\Requests\CommentRequest;
use App\Models\BloodRequestPost;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create(CommentRequest $request, BloodRequestPost $post)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['post_id'] = $post->id;

        $comment = Comment::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $comment,
            'message' => 'Comment created successfully',
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully',
        ]);
    }
}
