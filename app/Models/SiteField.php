<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteField extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'key', 'label', 'type', 'column'];
}
