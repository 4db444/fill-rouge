<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentDeleteRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store (CommentCreateRequest $request, Post $post) {
        $comment = $post->comments()->create([
            ...$request->validated(),
            "user_id" => Auth::user()->id
        ]);

        $comment->load([
            "user:id,first_name,last_name",
            "user.profile"
        ]);

        return response()->json([
            "comment" => $comment,
        ], 201);
    }

    public function destroy (CommentDeleteRequest $request, Post $post, Comment $comment) {
        $comment->delete();

        return response()->json([
            "message" => "message deleted successfully !"
        ], 200);
    }
}
