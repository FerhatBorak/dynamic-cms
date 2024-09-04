<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasRole('super_admin') || $user->hasRole('content_editor');
    }

    public function create(User $user)
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasRole('super_admin');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasRole('super_admin');
    }
}
