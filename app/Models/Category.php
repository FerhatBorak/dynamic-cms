<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            \Log::info('Saving Category:', $category->toArray());
        });

        static::saved(function ($category) {
            \Log::info('Saved Category:', $category->toArray());
        });
    }
    public function contents():HasMany
    {
        return $this->hasMany(Content::class);
    }
    public function fields()
{
    return $this->hasMany(CategoryField::class)->orderBy('order');
}
}
