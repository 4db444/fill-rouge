<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreationRequest;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index () {
        $user = Auth::user();

        // Sent requests: posts this user has requested to join (pending only)
        $sentRequests = $user->requests()
            ->wherePivot('status', 'pending')
            ->with('user.profile')
            ->get();

        // Received requests: pending requests on posts owned by this user
        $receivedRequests = Post::where('user_id', $user->id)
            ->whereHas('requests', function ($query) {
                $query->where('requests.status', 'pending');
            })
            ->with(['requests' => function ($query) {
                $query->where('requests.status', 'pending');
            }, 'requests.profile'])
            ->get();

        return view('requests.index', compact('sentRequests', 'receivedRequests'));
    }

    public function toggle_request (RequestCreationRequest $request, Post $post) {
        $user = Auth::user();

        // dd($post->requests()->where("users.id", $user->id)->wherePivot("status", "accepted")->get());
        
        $is_requested = $post->requests()->where("users.id", $user->id)->exists();

        if($is_requested) {
            $post->requests()->detach($user);
        }else {
            $post->requests()->attach($user, ['status' => 'pending']);
        }

        return redirect()->back();
    }

    public function cancel (Post $post) {
        $user = Auth::user();

        $post->requests()->detach($user->id);

        return redirect()->back()->with('success', 'Request cancelled.');
    }

    public function accept (Post $post, User $user) {
        $currentUser = Auth::user();

        // Only the post owner can accept
        if ($post->user_id !== $currentUser->id) {
            abort(403);
        }

        DB::transaction(function () use ($post, $user, $currentUser) {
            // Update status to accepted
            $post->requests()->updateExistingPivot($user->id, ['status' => 'accepted']);

            // Check if post already has a group
            if ($post->group_id) {
                $group = $post->group;
                // Add the requesting user if not already a member
                if (!$group->members()->where('users.id', $user->id)->exists()) {
                    $group->members()->attach($user->id);
                }
            } else {
                // Create a new group with the post title as name
                $group = Group::create([
                    'name' => $post->title,
                    'user_id' => $currentUser->id,
                ]);

                // Link the group to the post
                $post->group_id = $group->id;
                $post->save();

                // Add the post owner as a member
                if (!$group->members()->where('users.id', $currentUser->id)->exists()) {
                    $group->members()->attach($currentUser->id);
                }

                // Add the requesting user as a member
                $group->members()->attach($user->id);
            }
        });

        return redirect()->back()->with('success', 'Request accepted. User has been added to the group.');
    }

    public function reject (Post $post, User $user) {
        $currentUser = Auth::user();

        // Only the post owner can reject
        if ($post->user_id !== $currentUser->id) {
            abort(403);
        }

        // Delete the request from the pivot table
        $post->requests()->detach($user->id);

        return redirect()->back()->with('success', 'Request rejected.');
    }
}
