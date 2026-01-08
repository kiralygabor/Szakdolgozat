<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Advertisment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'advertisement_id' => 'required|exists:advertisments,id',
            'description' => 'required|string|min:10|max:1000',
            'reported_account_id' => 'required|exists:users,id'
        ]);

        $targetUser = \App\Models\User::findOrFail($validated['reported_account_id']);

        $report = Report::create([
            'advertisement_id' => $validated['advertisement_id'],
            'description' => $validated['description'],
            'reporter_account_id' => auth()->user()->account_id,
            'reported_account_id' => $targetUser->account_id,
            'status' => 'open'
        ]);

        return redirect()->back()->with('success', 'Report submitted successfully. Our team will review it shortly.');
    }
}
