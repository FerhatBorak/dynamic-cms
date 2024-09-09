<?php

use App\Models\HomepageSection;
use App\Models\HomepageContent;
use App\Presenters\HomepageContentPresenter;

if (!function_exists('get_homepage')) {
    function get_homepage($sectionSlug, $languageCode = null)
    {
        $currentLanguage = $languageCode ?? current_language()->code;

        $section = HomepageSection::where('slug', $sectionSlug)->first();
        if (!$section) {
            return null;
        }

        $content = HomepageContent::where('homepage_section_id', $section->id)->first();
        if (!$content) {
            return null;
        }

        // Eğer belirtilen dilde içerik yoksa, varsayılan dili kullan
        $contentData = $content->content[$currentLanguage] ?? $content->content[config('app.fallback_locale')] ?? null;

        if (!$contentData) {
            return null;
        }

        return new HomepageContentPresenter($content, $currentLanguage);
    }
}
