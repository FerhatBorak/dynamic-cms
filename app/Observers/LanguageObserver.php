<?php

namespace App\Observers;

use App\Models\Language;
use App\Models\Category;
use App\Models\Content;
use App\Models\ContentTranslation;

class LanguageObserver
{
    public function created(Language $language)
    {
        // Yeni dil eklendiğinde tüm kategoriler ve içerikler için çeviri oluştur
        Category::chunk(100, function ($categories) use ($language) {
            foreach ($categories as $category) {
                $defaultTranslation = $category->translations()->where('locale', config('app.fallback_locale'))->first();
                $category->translations()->create([
                    'locale' => $language->code,
                    'name' => $defaultTranslation ? $defaultTranslation->name : $category->name,
                    'description' => $defaultTranslation ? $defaultTranslation->description : '',
                ]);
            }
        });

        Content::chunk(100, function ($contents) use ($language) {
            foreach ($contents as $content) {
                $defaultTranslation = $content->translations()->where('locale', config('app.fallback_locale'))->first();
                if ($defaultTranslation) {
                    ContentTranslation::create([
                        'content_id' => $content->id,
                        'locale' => $language->code,
                        'title' => $defaultTranslation->title,
                        'slug' => $defaultTranslation->slug,
                        'fields' => $defaultTranslation->fields,
                    ]);
                }
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
