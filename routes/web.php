<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModeratorController;
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

Route::middleware(["auth", "banned"])->group(function() {
    // dashboard routes
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");
    Route::get("/dashboard/search", [DashboardController::class, "search"])->name("dashboard.search");

    // profile routes
    Route::prefix("/profile")->group(function() {
        Route::put("/{user}/info", [UserController::class, "updateInfo"])->name("user.info.update");
        Route::put("/{user}/password", [UserController::class, "updatePassword"])->name("user.password.update");
        Route::post("/{user}/report", [ReportController::class, "store"])->name("user.report");
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
        
        Route::post("/{post}/requests", [RequestController::class, "toggle_request"])->name("post.toggle_request");
    });

    // request management routes
    Route::prefix("/requests")->group(function () {
        Route::get("/", [RequestController::class, "index"])->name("requests.index");
        Route::delete("/{post}/cancel", [RequestController::class, "cancel"])->name("requests.cancel");
        Route::post("/{post}/accept/{user}", [RequestController::class, "accept"])->name("requests.accept");
        Route::delete("/{post}/reject/{user}", [RequestController::class, "reject"])->name("requests.reject");
    });

    // group expense management routes
    Route::prefix("/groups")->group(function () {
        Route::get("/", [GroupController::class, "index"])->name("groups.index");
        Route::get("/{group}", [GroupController::class, "show"])->name("groups.show");
        Route::post("/{group}/leave", [GroupController::class, "leaveGroup"])->name("groups.leave");
        Route::delete("/{group}/members/{member}", [GroupController::class, "removeMember"])->name("groups.members.remove");
        Route::post("/{group}/expenses", [GroupController::class, "storeExpense"])->name("groups.expenses.store");
        Route::delete("/{group}/expenses/{expense}", [GroupController::class, "deleteExpense"])->name("groups.expenses.delete");
        Route::post("/{group}/settlements", [GroupController::class, "storeSettlement"])->name("groups.settlements.store");
        Route::post("/{group}/settlements/{settlement}/verify", [GroupController::class, "verifySettlement"])->name("groups.settlements.verify");
        Route::delete("/{group}/settlements/{settlement}/reject", [GroupController::class, "rejectSettlement"])->name("groups.settlements.reject");
    });

    // admin routes
    Route::middleware("admin")->prefix("/admin")->group(function () {
        Route::get("/", [AdminController::class, "index"])->name("admin.dashboard");
        Route::put("/users/{user}/role", [AdminController::class, "updateRole"])->name("admin.users.role");
    });

    // moderator routes
    Route::middleware("moderator")->prefix("/moderator")->group(function () {
        Route::get("/", [ModeratorController::class, "index"])->name("moderator.dashboard");
        Route::get("/users/{user}/reports", [ModeratorController::class, "userReports"])->name("moderator.user.reports");
        Route::put("/users/{user}/ban", [ModeratorController::class, "toggleBan"])->name("moderator.users.ban");
    });
});