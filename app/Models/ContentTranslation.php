<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'locale', 'title', 'content', 'fields'];

    protected $casts = [
        'fields' => 'array',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
    public function fields()
{
    return $this->hasMany(ContentTranslationField::class);
}
}
