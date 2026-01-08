<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|min:10|max:1000',
            'reported_account_id' => 'required|exists:users,account_id'
        ]);

        \App\Models\UserReport::create([
            'description' => $validated['description'],
            'reporter_account_id' => auth()->user()->account_id,
            'reported_account_id' => $validated['reported_account_id'],
            'status' => 'open'
        ]);

        return redirect()->back()->with('success', 'User reported successfully. Our team will review it shortly.');
    }
}
