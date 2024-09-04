<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('site_setting')) {
    function site_setting($key, $default = null)
    {
        $cacheKey = "site_setting.{$key}";

        if (!Cache::has($cacheKey)) {
            $value = SiteSetting::where('key', $key)->value('value');
            Cache::forever($cacheKey, $value);
        }

        return Cache::get($cacheKey) ?? $default;
    }
}
