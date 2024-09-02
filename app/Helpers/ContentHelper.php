<?php

use App\Models\Content;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Presenters\ContentPresenter;

if (!function_exists('get_category_items')) {
    function get_category_items($categorySlug, $limit = null, $paginate = false)
    {
        $currentLanguage = current_language()->code;

        $category = Category::where('slug', $categorySlug)->first();
        if (!$category) {
            return collect();
        }

        $query = $category->contents()
            ->with(['translations' => function($query) use ($currentLanguage) {
                $query->where('locale', $currentLanguage);
            }])
            ->whereHas('translations', function($query) use ($currentLanguage) {
                $query->where('locale', $currentLanguage);
            });

        if ($limit && !$paginate) {
            $query->limit($limit);
        }

        $contents = $paginate ? $query->paginate($limit ?? 15) : $query->get();

        return $contents->map(function ($content) {
            return new ContentPresenter($content);
        });
    }
}
if (!function_exists('get_content_field')) {
    function get_content_field($content, $fieldName, $default = null)
    {
        return $content['fields'][$fieldName] ?? $default;
    }
}
if (!function_exists('get_content')) {
    function get_content($slug)
    {
        $currentLanguage = current_language()->code;

        $content = Content::join('content_translations', 'contents.id', '=', 'content_translations.content_id')
            ->join('categories', 'contents.category_id', '=', 'categories.id')
            ->join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('content_translations.slug', $slug)
            ->where('content_translations.locale', $currentLanguage)
            ->where('category_translations.locale', $currentLanguage)
            ->select('contents.*', 'content_translations.title', 'content_translations.slug', 'content_translations.fields',
                     'categories.slug as category_slug', 'category_translations.name as category_name')
            ->first();

        if (!$content) {
            return null;
        }

        return array_merge($content->toArray(), [
            'fields' => json_decode($content->fields, true) ?? [],
            'category' => [
                'slug' => $content->category_slug,
                'name' => $content->category_name,
            ],
        ]);
    }
}
