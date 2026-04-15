<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * Switch the application locale.
     */
    public function switch(string $locale): RedirectResponse
    {
        $supportedLocales = config('app.supported_locales', ['en', 'hu']);

        if (in_array($locale, $supportedLocales)) {
            session(['locale' => $locale]);
            App::setLocale($locale);

            if (Auth::check()) {
                Auth::user()->update(['locale' => $locale]);
            }
        }

        return back();
    }
}
