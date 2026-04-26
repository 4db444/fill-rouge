<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends Controller
{
    public function index()
    {
        $reports = Report::with(['reporter', 'reported'])->latest()->paginate(20);
        return view('moderator.dashboard', compact('reports'));
    }

    public function userReports(User $user)
    {
        $reports = Report::where('reported_id', $user->id)
            ->with('reporter')
            ->latest()
            ->paginate(20);

        return view('moderator.user_reports', compact('reports', 'user'));
    }

    public function toggleBan(User $user)
    {
        // Prevent banning self
        if ($user->id === Auth::id()) {
            return back()->withErrors(['ban' => 'You cannot ban yourself.']);
        }

        // Prevent banning admin or other moderators
        if (in_array($user->role, ['admin', 'moderator'])) {
            return back()->withErrors(['ban' => 'You cannot ban an admin or a moderator.']);
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $action = $user->is_banned ? 'banned' : 'unbanned';
        return back()->with('success', "User has been {$action} successfully.");
    }
}
