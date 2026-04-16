<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix("/auth")->group(function () {
    Route::get("/login", [AuthController::class, "showLoginPage"])->name("login");
    Route::get("/signup", [AuthController::class, "showRegistrationPage"]);
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/signup", [AuthController::class, "register"]);
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
});

Route::middleware("auth")->group(function() {
    // dashboard route
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");

    // profile routes
    Route::prefix("/profile")->group(function() {
        Route::put("/{user}/info", [UserController::class, "updateInfo"])->name("user.info.update");
        Route::put("/{user}/password", [UserController::class, "updatePassword"])->name("user.password.update");
        Route::get("/{user?}", [UserController::class, "show"])->name("user.profile")
            ->missing(function () {
                return redirect()->route("dashboard");
            });
    });

    // post routes
    Route::prefix("/posts")->group (function () {
        Route::post("/", [PostController::class, "store"])->name("post.create");
        Route::post("/{post}/toggle_like", [PostController::class, "toggle_like"])->name("post.toggle_like");
        Route::get("/{post}", [PostController::class, "show"])->name("post.show");
        
        Route::post("/{post}/comments", [CommentController::class, "store"])->name("post.comment.create");
        Route::delete("/{post}/comments/{comment}", [CommentController::class, "destroy"])->name("post.comment.delete");
    });
});