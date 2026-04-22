<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index () {
        $user = Auth::user();
        $posts = Post::latest()
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
}
