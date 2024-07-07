<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = ['meta_title', 'meta_description', 'og_title', 'og_description', 'og_image'];

    public function seoable()
    {
        return $this->morphTo();
    }
}
