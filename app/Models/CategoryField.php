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
        'min_length',
        'max_length',
        'rows',
        'min',
        'max',
        'step',
        'min_date',
        'max_date',
        'options',
        'allowed_file_types',
        'max_file_size',
        'help_text',
        'default_value',
        'is_required',
        'is_unique',
        'default_value',
        'validation_rules',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'options' => 'array',
        'validation_rules' => 'array',
        'min_date' => 'date',
        'max_date' => 'date',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }
    public function group() {
        return $this->belongsTo(CategoryFieldGroup::class,'group_id');
    }
    public function translations()
    {
        return $this->hasMany(CategoryFieldTranslation::class);
    }

    public function getTranslation($locale)
    {

        return $this->translations()->where('locale', $locale)->first();
    }
}
