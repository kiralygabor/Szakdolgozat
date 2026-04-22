<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\UserReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReportController extends Controller
{
    public function store(\App\Http\Requests\Report\StoreUserReportRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        UserReport::create([
            'description' => $validated['description'],
            'reporter_account_id' => Auth::user()->account_id,
            'reported_account_id' => $validated['reported_account_id'],
            'status' => ReportStatus::Open,
        ]);

        return back()->with('success', __('User reported successfully. Our team will review it shortly.'));
    }
}
