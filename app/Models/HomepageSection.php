<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug','column_span'];

    public function fields()
    {
        return $this->hasMany(HomepageField::class);
    }

    public function content()
    {
        return $this->hasOne(HomepageContent::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($section) {
            $section->content()->create();
        });
    }
}
