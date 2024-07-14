<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class FieldType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'config'];

    protected $casts = [
        'config' => 'array',
    ];

    public function categoryFields(): HasMany
    {
        return $this->hasMany(CategoryField::class);
    }

    public function getConfigAttribute($value)
    {
        Log::info("Raw Config Value: " . $value);

        $defaultConfig = [
            'has_options' => false,
            'has_min_max' => false,
            'has_min_max_length' => false,
            'has_step' => false,
            'has_rows' => false,
            'has_file_options' => false,
        ];

        if (is_null($value)) {
            Log::info("Config is null, returning default");
            return $defaultConfig;
        }

        if (is_string($value)) {
            $decodedValue = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decodedValue;
                Log::info("Decoded Config: " . json_encode($value));
            } else {
                Log::info("Failed to decode config, returning default");
                return $defaultConfig;
            }
        }

        if (!is_array($value)) {
            Log::info("Config is not an array, returning default");
            return $defaultConfig;
        }

        $mergedConfig = array_merge($defaultConfig, $value);
        Log::info("Merged Config: " . json_encode($mergedConfig));
        return $mergedConfig;
    }
}
