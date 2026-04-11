<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");
    Route::put("/profile/{user}/info", [UserController::class, "updateInfo"])->name("user.info.update");
    Route::put("/profile/{user}/password", [UserController::class, "updatePassword"])->name("user.password.update");
    Route::get("/profile/{user?}", [UserController::class, "show"])
        ->missing(function () {
            return redirect()->route("dashboard");
        });
});