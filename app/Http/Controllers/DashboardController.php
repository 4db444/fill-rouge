<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index () {
        $user = Auth::user();
        $posts = Post::latest()
                     ->with(['images', 'user.profile'])
                     ->withCount([
                        "likes",
                        "comments",
                        "likes as is_liked" => function ($query) use($user) {
                            $query->where("user_id", $user->id);
                        },
                        "requests as is_requested" => function ($query) use($user) {
                            $query->where("id", $user->id);
                        },
                        "requests as is_joined" => function ($query) use($user) {
                            $query->where("users.id", $user->id)->where("status", "accepted");
                        }
                     ])
                     ->get();

        return view("dashboard.dashboard", compact(
            "posts"
        ));
    }

    public function search (Request $request) {
        $query = $request->input("q", "");

        // handle smaller queries.
        if (strlen(trim($query)) < 2) {
            return response()->json(["posts" => [], "users" => []]);
        }

        $keyword = "%" . trim($query) . "%";

        // look for the keyword in the posts (title, content, addess)
        $posts = Post::where("title", "like", $keyword)
            ->orWhere("content", "like", $keyword)
            ->orWhere("address", "like", $keyword)
            ->latest()
            ->withCount(["likes", "comments"])
            ->with("user.profile")
            ->get()
            ->map(function ($post) {
                return [
                    "id" => $post->id,
                    "title" => $post->title,
                    "content" => \Illuminate\Support\Str::limit($post->content, 120),
                    "address" => $post->address,
                    "likes_count" => $post->likes_count,
                    "comments_count" => $post->comments_count,
                    "author" => $post->user->first_name . " " . $post->user->last_name,
                    "author_image" => $post->user->profile->img_url ?? "storage/images/profiles/default.png",
                    "created_at" => $post->created_at->diffForHumans(),
                ];
            });

        // Search users by name or email
        $users = User::where("first_name", "like", $keyword)
            ->orWhere("last_name", "like", $keyword)
            ->orWhere("email", "like", $keyword)
            ->with("profile")
            ->get()
            ->map(function ($user) {
                return [
                    "id" => $user->id,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "city" => $user->city,
                    "country" => $user->country,
                    "profile_image" => $user->profile->img_url ?? "storage/images/profiles/default.png",
                ];
            });

        return response()->json([
            "posts" => $posts,
            "users" => $users
        ]);
    }
}
