<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(ContentTranslation::class);
    }

    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $fallbackLocale = config('app.fallback_locale');

        $translation = $this->translations()->where('locale', $locale)->first();

        if (!$translation && $locale !== $fallbackLocale) {
            $translation = $this->translations()->where('locale', $fallbackLocale)->first();
        }

        return $translation ? $translation->$field : null;
    }
    public function getTable()
{
    return 'contents';
}

protected static function boot()
{
    parent::boot();

    static::saving(function ($content) {
        if (request()->has('translations')) {
            $translations = request()->input('translations');
            foreach ($translations as $locale => $data) {
                $fields = $data['fields'] ?? [];
                $content->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title' => $data['title'],
                        'fields' => $fields,
                    ]
                );
            }
        }
    });
}

}
