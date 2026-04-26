<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalGroups = Group::count();
        $totalPosts = Post::count();

        $users = User::paginate(20);

        return view('admin.dashboard', compact('totalUsers', 'totalGroups', 'totalPosts', 'users'));
    }

    public function updateRole(Request $request, User $user)
    {
        if ($user->id === auth()->user()->id) {
            return back()->withErrors(['role' => 'You cannot change your own role.']);
        }

        $request->validate([
            'role' => 'required|in:user,moderator'
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'User role updated successfully.');
    }
}
