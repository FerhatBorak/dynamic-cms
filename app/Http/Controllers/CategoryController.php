<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = get_category($slug);

        if (!$category) {
            abort(404);
        }

        $contents = get_category_items($slug, 10, true); // 10 içerik per sayfa, sayfalama aktif

        return view('category.show', compact('category', 'contents'));
    }
}
