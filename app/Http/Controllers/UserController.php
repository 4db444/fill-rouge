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

        return view("user.profile", compact("user"));
    }

    public function updateInfo (UserInfoUpdateRequest $request, User $user) {
        $user->update($request->validated());
        return redirect()->back();
    }

    public function updatePassword (UserPasswordUpdateRequest $request, User $user) {
        $user->password = Hash::make($request->validated()["new_password"]);
        $user->save();
        return redirect()->back();
    }
}
