<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(PostCreateRequest $request)
    {
        // dd($request->validated());
        $user = Auth::user();
        $user->posts()->create(
            $request->validated()
        );

        return redirect()-> back();
    }

    public function toggle_like (Post $post) {
        $user = Auth::user();
        $is_liked = $post->likes()->where("user_id", $user->id)->exists();

        $is_liked ? $post->likes()->detach($user) : $post->likes()->attach($user);

        return response()->json([
            "likes" => $post->likes()->count(),
            "is_liked" => !$is_liked
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }
    
    public function destroy(string $id)
    {
        //
    }
}
