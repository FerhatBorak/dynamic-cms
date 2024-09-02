<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Storage;

class ContentPresenter
{
    protected $content;
    protected $fields;
    protected $translation;

    public function __construct($content)
    {
        $this->content = $content;
        $this->translation = $content->translations->first();
        $this->fields = $this->translation->fields ?? [];
    }

    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->getFieldValue($name, $this->fields[$name]);
        }

        if (isset($this->translation->$name)) {
            return $this->translation->$name;
        }

        if (isset($this->content->$name)) {
            return $this->content->$name;
        }

        return null;
    }

    protected function getFieldValue($fieldName, $value)
    {
        if (is_string($value) && strpos($value, 'uploads/') === 0) {
            return get_file_url($value);
        }

        return $value;
    }

    public function getFields()
    {
        return $this->fields;
    }
}
