<?php

namespace App\Http\Controllers\Feed; // Fixed: Added \Feed

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Mail\NewCommentMail;
use App\Models\BloodRequestPost;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    public function create(CommentRequest $request, BloodRequestPost $post)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['post_id'] = $post->id;

        $comment = Comment::create($validated);
        $comment->load('user');

        $post->load('user');
        if ($post->user_id !== Auth::id()) {
            Mail::to($post->user->email)->send(new NewCommentMail($post->user, $comment, $post));
        }

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
