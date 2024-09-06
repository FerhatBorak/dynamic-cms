<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageField extends Model
{
    use HasFactory;

    protected $fillable = ['homepage_section_id', 'name', 'slug', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(HomepageSection::class);
    }
}
