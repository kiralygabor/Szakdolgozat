<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
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

        $supportedLocales = config('app.supported_locales', ['en', 'hu']);
        if (in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
