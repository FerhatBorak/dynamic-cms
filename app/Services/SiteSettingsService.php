<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class SiteSettingsService
{
    protected $editorUser;

    public function __construct()
    {
        $this->editorUser = Cache::remember('editor_user', 3600, function () {
            return User::role('editor')->first();
        });
    }

    public function get($key)
    {
        return $this->editorUser->{$key} ?? null;
    }

    public function refreshCache()
    {
        Cache::forget('editor_user');
        $this->editorUser = User::role('editor')->first();
        Cache::put('editor_user', $this->editorUser, 3600);
    }
}
