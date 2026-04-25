<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserInfoUpdateRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index () {

    }

    public function show (?User $user = null) {
        $user = $user ?? Auth::user();

        // Load profile image
        $user->load('profile');

        // Load user's posts with counts
        $posts = $user->posts()
            ->latest()
            ->withCount(['likes', 'comments'])
            ->with('images')
            ->get();

        return view("user.profile", compact("user", "posts"));
    }

    public function updateInfo (UserInfoUpdateRequest $request, User $user) {
        // Update basic user info
        $user->update($request->safe()->only(['first_name', 'last_name', 'bio', 'city', 'country']));

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('images/profiles', 'public');
            $imgUrl = 'storage/' . $path;

            // Update existing profile image or create a new one
            if ($user->profile) {
                $user->profile->update(['img_url' => $imgUrl]);
            } else {
                $user->profile()->create(['img_url' => $imgUrl]);
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword (UserPasswordUpdateRequest $request, User $user) {
        $user->password = Hash::make($request->validated()["new_password"]);
        $user->save();
        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
