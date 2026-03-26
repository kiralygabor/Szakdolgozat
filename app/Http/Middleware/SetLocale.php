<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (!$locale && auth()->check()) {
            $locale = auth()->user()->locale;
            if ($locale) {
                session(['locale' => $locale]);
            }
        }

        if (!$locale) {
            $locale = config('app.locale', 'en');
        }

        if (in_array($locale, ['en', 'hu'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
