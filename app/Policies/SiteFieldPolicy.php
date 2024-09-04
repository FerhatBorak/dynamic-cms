<?php

namespace App\Policies;

use App\Models\SiteField;
use App\Models\User;

class SiteFieldPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_site_fields');
    }

    public function view(User $user, SiteField $siteField): bool
    {
        return $user->hasPermission('manage_site_fields');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_site_fields');
    }

    public function update(User $user, SiteField $siteField): bool
    {
        return $user->hasPermission('manage_site_fields');
    }

    public function delete(User $user, SiteField $siteField): bool
    {
        return $user->hasPermission('manage_site_fields');
    }
}
