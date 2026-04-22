<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->hasIncompleteProfile()) {
            return redirect()
                ->route('profile')
                ->with('info', __('Please complete your profile before continuing.'));
        }

        return $next($request);
    }
}
