<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function preview(Request $request)
{
    $content = new Content($request->all());
    $category = Category::findOrFail($request->category_id);

    return view('content.preview', compact('content', 'category'));
}


}
