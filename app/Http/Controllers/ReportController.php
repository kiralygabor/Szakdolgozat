<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'advertisement_id' => 'required|exists:advertisements,id',
            'description' => 'required|string|min:10|max:1000',
            'reported_account_id' => 'required|exists:users,id',
        ]);

        $targetUser = User::findOrFail($validated['reported_account_id']);

        Report::create([
            'advertisement_id' => $validated['advertisement_id'],
            'description' => $validated['description'],
            'reporter_account_id' => auth()->user()->account_id,
            'reported_account_id' => $targetUser->account_id,
            'status' => 'open',
        ]);

        return back()->with('success', __('Report submitted successfully.'));
    }
}
