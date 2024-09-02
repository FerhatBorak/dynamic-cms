<?php

use App\Models\Language;

if (!function_exists('current_language')) {
    function current_language()
    {
        $code = session('language', config('app.locale'));
        return Language::where('code', $code)->first() ?? Language::where('code', config('app.fallback_locale'))->first();
    }
}

if (!function_exists('get_active_languages')) {
    function get_active_languages()
    {
        return Language::where('is_active', true)->get();
    }
}
