<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryFieldGroup extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'order'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fields()
    {
        return $this->hasMany(CategoryField::class, 'group_id');
    }
}
