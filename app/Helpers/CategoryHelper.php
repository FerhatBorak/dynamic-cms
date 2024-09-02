
<?php

use App\Models\Category;

if (!function_exists('get_category')) {
    function get_category($slug)
    {
        $currentLanguage = current_language()->code;

        $category = Category::join('category_translations', 'categories.id', '=', 'category_translations.category_id')
            ->where('categories.slug', $slug)
            ->where('category_translations.locale', $currentLanguage)
            ->select('categories.*', 'category_translations.name', 'category_translations.description')
            ->first();

        if (!$category) {
            return null;
        }

        return $category->toArray();
    }
}
