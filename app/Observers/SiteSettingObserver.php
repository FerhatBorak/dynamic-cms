<?php

namespace App\Observers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingObserver
{
    public function saved(SiteSetting $siteSetting)
    {
        $this->clearCache($siteSetting);
    }

    public function deleted(SiteSetting $siteSetting)
    {
        $this->clearCache($siteSetting);
    }

    private function clearCache(SiteSetting $siteSetting)
    {
        Cache::forget("site_setting.{$siteSetting->key}");
    }
}
