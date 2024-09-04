<?php

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;

class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_site_settings');
    }

    public function view(User $user, SiteSetting $siteSetting): bool
    {
        return $user->hasPermission('manage_site_settings');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_site_settings');
    }

    public function update(User $user, SiteSetting $siteSetting): bool
    {
        return $user->hasPermission('manage_site_settings');
    }

    public function delete(User $user, SiteSetting $siteSetting): bool
    {
        return $user->hasPermission('manage_site_settings');
    }
}
