<?php

namespace App\Policies;

use App\Models\Content;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContentPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('content_editor');
    }

    public function create(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('content_editor');
    }

    public function update(User $user, Content $content)
    {
        return $user->hasRole('super_admin') ||
               ($user->hasRole('content_editor') && $user->hasPermission('edit_' . $content->category->slug));
    }

    public function delete(User $user, Content $content)
    {
        return $user->hasRole('super_admin') ||
               ($user->hasRole('content_editor') && $user->hasPermission('delete_' . $content->category->slug));
    }
}
