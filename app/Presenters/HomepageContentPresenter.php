<?php

namespace App\Presenters;

class HomepageContentPresenter
{
    protected $content;
    protected $language;

    public function __construct($content, $language)
    {
        $this->content = $content;
        $this->language = $language;
    }

    public function __get($name)
    {
        $contentData = $this->content->content[$this->language]
            ?? $this->content->content[config('app.fallback_locale')]
            ?? null;

        if (!$contentData) {
            return null;
        }

        return $contentData[$name] ?? null;
    }

    public function toArray()
    {
        return $this->content->content[$this->language]
            ?? $this->content->content[config('app.fallback_locale')]
            ?? [];
    }
}
