<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentVersion extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'data', 'version', 'created_by'];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
