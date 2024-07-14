<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'locale', 'title', 'slug', 'fields'];

    protected $casts = [
        'fields' => 'array',
    ];
    public function getTable()
{
    return 'content_translations';
}

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
