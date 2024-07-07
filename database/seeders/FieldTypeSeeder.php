<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldType;

class FieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fieldTypes = [
            ['name' => 'Text', 'slug' => 'text'],
            ['name' => 'Textarea', 'slug' => 'textarea'],
            ['name' => 'Rich Text', 'slug' => 'rich_text'],
            ['name' => 'Number', 'slug' => 'number'],
            ['name' => 'Date', 'slug' => 'date'],
            ['name' => 'Select', 'slug' => 'select'],
            ['name' => 'Checkbox', 'slug' => 'checkbox'],
            ['name' => 'File', 'slug' => 'file'],
        ];

        foreach ($fieldTypes as $fieldType) {
            FieldType::updateOrCreate(['slug' => $fieldType['slug']], $fieldType);
        }
    }
}
