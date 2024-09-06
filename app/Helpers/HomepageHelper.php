<?php

use App\Models\HomepageSection;
use App\Models\HomepageContent;

if (!function_exists('get_homepage')) {
    function get_homepage($sectionSlug, $languageCode = null)
    {
        $languageCode = $languageCode ?? app()->getLocale();

        $section = HomepageSection::where('slug', $sectionSlug)->first();

        if (!$section) return null;

        $content = HomepageContent::where('homepage_section_id', $section->id)
            ->where('language_code', $languageCode)
            ->first();


        if (!$content) return null;

        return new class($content->content) {
            public $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function __get($name)
            {
                return $this->data[$name] ?? null;
            }

            public function toArray()
            {
                return $this->data;
            }
        };
    }
}
