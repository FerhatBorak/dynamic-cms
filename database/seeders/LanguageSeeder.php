<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        Language::create([
            'name' => 'Türkçe',
            'code' => 'tr',
            'icon' => 'heroicon-o-flag', // Heroicon kullanıyoruz
            'is_default' => 1, // Heroicon kullanıyoruz
            'is_active' => true,
        ]);
    }
}
