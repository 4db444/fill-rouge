<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginPage(){
        return view("auth.login");
    }

    public function showRegistrationPage () {
        return view("auth.signup");
    }

    public function login (UserLoginRequest $request) {
        if(Auth::attempt($request->validated())){
            $request->session()->regenerate();

            return redirect()->intended(route("dashboard"));
        }

        return redirect()->back()->withErrors([
            "wrong credentials !"
        ]);
    }

    public function register (UserRegistrationRequest $request) {
        $user = User::create([
            ...$request->validated(),
            "password" => Hash::make($request->safe()->password),
            "role" => "user"
        ]);

        $user->profile()->create([
            "img_url" => "storage/images/profiles/default.png"
        ]);

        return redirect()->route("login");
    }

    public function logout (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/auth/login");
    }
}
