<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function show($slug)
    {
        $content = get_content($slug);

        if (!$content) {
            abort(404);
        }

        return view('content.show', compact('content'));
    }
}
