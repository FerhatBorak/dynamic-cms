<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
