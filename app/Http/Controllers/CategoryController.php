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

        $contents = get_category_items($slug, 10, true);

        return view('category.show', compact('category', 'contents'));
    }
}
