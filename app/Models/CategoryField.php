<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryField extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'field_type_id',
        'name',
        'slug',
        'label',
        'placeholder',
        'help_text',
        'is_required',
        'is_unique',
        'min',
        'max',
        'step',
        'options',
        'default_value',
        'validation_rules',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'options' => 'array',
        'validation_rules' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }
}
