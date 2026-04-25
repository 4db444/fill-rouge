<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportCreateRequest;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Store a new report against a user.
     */
    public function store(ReportCreateRequest $request, User $user)
    {
        Report::create([
            "reporter_id" => Auth::user()->id,
            "reported_id" => $user->id,
            "message" => $request->validated()["message"]
        ]);

        return redirect()->back()->with("success", "Report submitted successfully.");
    }
}
