<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Permission extends Model
{
    protected $fillable = ['name', 'label'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

// app/Models/Permission.php

public function scopeActiveCategories(Builder $query)
{
    $activeCategorySlugs = Category::pluck('slug')->map(fn($slug) => 'edit_' . $slug)->toArray();

    return $query->where(function($q) use ($activeCategorySlugs) {
        $q->whereIn('name', $activeCategorySlugs)
          ->orWhere('name', 'not like', 'edit_%'); // Diğer izinleri de dahil et
    });
}
}
