<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

use Illuminate\Database\Eloquent\Builder;


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
        'validation_rules',
        'order',
        'type_specific_config',
        'column_span',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'validation_rules' => 'array',
        'type_specific_config' => 'array',
        'column_span' =>'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }
    public static function uniqueSlugRule($categoryId, $ignoreId = null)
    {
        return Rule::unique('category_fields', 'slug')
            ->where(function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->ignore($ignoreId);
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }
}
