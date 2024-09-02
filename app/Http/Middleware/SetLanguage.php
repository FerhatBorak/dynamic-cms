<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLanguage
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('lang')) {
            $language = Language::where('code', $request->lang)->where('is_active', true)->first();
            if ($language) {
                session(['language' => $language->code]);
            }
        } elseif (!session()->has('language')) {
            $defaultLanguage = Language::where('is_default', true)->first()
                ?? Language::where('code', config('app.fallback_locale'))->first();
            session(['language' => $defaultLanguage->code]);
        }

        app()->setLocale(session('language'));

        return $next($request);
    }
}
