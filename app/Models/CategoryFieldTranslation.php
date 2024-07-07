<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryFieldTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['category_field_id', 'locale', 'label', 'placeholder', 'help_text'];

    public function categoryField()
    {
        return $this->belongsTo(CategoryField::class);
    }
}
