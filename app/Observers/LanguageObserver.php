<?php

namespace App\Observers;

use App\Models\Language;
use App\Models\Category;
use App\Models\Content;

class LanguageObserver
{
    public function created(Language $language)
    {
        // Yeni dil eklendiğinde tüm kategoriler ve içerikler için çeviri oluştur
        Category::chunk(100, function ($categories) use ($language) {
            foreach ($categories as $category) {
                $category->translations()->create([
                    'locale' => $language->code,
                    'name' => $category->name,
                    'description' => $category->description,
                ]);
            }
        });

        Content::chunk(100, function ($contents) use ($language) {
            foreach ($contents as $content) {
                $content->translations()->create([
                    'locale' => $language->code,
                    'title' => $content->getTranslation('title', config('app.fallback_locale')),
                    'slug' => $content->getTranslation('slug', config('app.fallback_locale')),
                    'fields' => $content->getTranslation('fields', config('app.fallback_locale')),
                ]);
            }
        });
    }

    public function deleted(Language $language)
    {
        // Dil silindiğinde ilgili çevirileri sil
        \DB::table('category_translations')->where('locale', $language->code)->delete();
        \DB::table('content_translations')->where('locale', $language->code)->delete();
    }
}
