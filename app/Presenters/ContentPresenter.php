<?php

namespace App\Presenters;

class ContentPresenter
{
    protected $content;
    protected $fields;

    public function __construct($content)
    {
        $this->content = $content;
        $this->fields = $content->translations->first()->fields ?? [];
    }

    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        if (method_exists($this, $name)) {
            return $this->$name();
        }

        return $this->content->$name;
    }

    public function title()
    {
        return $this->content->translations->first()->title ?? '';
    }

    public function slug()
    {
        return $this->content->translations->first()->slug ?? '';
    }

    // Diğer ortak metodları burada tanımlayabilirsiniz
}
