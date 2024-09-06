<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    use HasFactory;

    protected $fillable = ['homepage_section_id', 'content'];

    protected $casts = [
        'content' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(HomepageSection::class, 'homepage_section_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($content) {
            $languages = Language::all();
            $emptyContent = array_fill_keys($languages->pluck('code')->toArray(), []);
            $content->update(['content' => $emptyContent]);
        });
    }
}
