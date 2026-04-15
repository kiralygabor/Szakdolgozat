<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(\App\Http\Requests\Report\StoreReportRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $targetUser = User::findOrFail($validated['reported_account_id']);

        Report::create([
            'advertisement_id' => $validated['advertisement_id'],
            'description' => $validated['description'],
            'reporter_account_id' => Auth::user()->account_id,
            'reported_account_id' => $targetUser->account_id,
            'status' => ReportStatus::Open,
        ]);

        return back()->with('success', __('Report submitted successfully.'));
    }
}
