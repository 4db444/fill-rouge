<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
});