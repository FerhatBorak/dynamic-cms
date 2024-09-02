<?php

use App\Models\Content;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Presenters\ContentPresenter;
use Illuminate\Support\Facades\Storage;

if (!function_exists('get_file_url')) {
    function get_file_url($path)
    {
        if (empty($path)) {
            return null;
        }
        return Storage::url($path);
    }
}

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

        if ($paginate) {
            $contents = $query->paginate($limit ?? 15);
            return new \Illuminate\Pagination\LengthAwarePaginator(
                $contents->map(function ($content) {
                    return new ContentPresenter($content);
                }),
                $contents->total(),
                $contents->perPage(),
                $contents->currentPage(),
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $contents = $limit ? $query->limit($limit)->get() : $query->get();
            return $contents->map(function ($content) {
                return new ContentPresenter($content);
            });
        }
    }
}

if (!function_exists('get_content')) {
    function get_content($slug)
    {
        $currentLanguage = current_language()->code;

        $content = Content::with(['translations' => function($query) use ($currentLanguage) {
            $query->where('locale', $currentLanguage);
        }])
        ->whereHas('translations', function($query) use ($currentLanguage, $slug) {
            $query->where('locale', $currentLanguage)->where('slug', $slug);
        })
        ->first();

        if (!$content) {
            return null;
        }

        return new ContentPresenter($content);
    }
}
