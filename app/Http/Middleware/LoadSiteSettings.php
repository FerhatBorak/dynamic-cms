<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\SiteSetting;

class LoadSiteSettings
{
    public function handle(Request $request, Closure $next)
    {
        if (!Cache::has('site_settings')) {
            $settings = SiteSetting::all()->keyBy('key')->map(function ($setting) {
                return $setting->value;
            })->toArray();

            Cache::forever('site_settings', $settings);
        }

        return $next($request);
    }
}
