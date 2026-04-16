<?php

namespace App\Http\Controllers;

use App\Http\Requests\BloodPostRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(BloodPostRequest $request)
    {
        $validated = $request->validated();
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
