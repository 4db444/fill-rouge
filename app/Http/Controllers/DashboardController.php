<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index () {
        $posts = Post::latest()
                     ->withCount("likes")
                     ->withCount(["likes as is_liked" => function ($query) {
                        $query->where("user_id", Auth::user()->id);
                     }])
                     ->get();

        return view("dashboard.dashboard", compact(
            "posts"
        ));
    }
}
