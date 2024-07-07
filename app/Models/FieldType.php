<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function categoryFields(): HasMany
    {
        return $this->hasMany(CategoryField::class);
    }
}
