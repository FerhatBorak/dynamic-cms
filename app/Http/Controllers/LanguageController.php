<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    public function changeLanguage($code)
    {
        $language = Language::where('code', $code)->where('is_active', true)->first();
        if ($language) {
            session(['language' => $code]);
        }
        return redirect()->back();
    }
}
