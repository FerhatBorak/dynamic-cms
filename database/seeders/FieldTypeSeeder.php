<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldType;
use Illuminate\Support\Facades\Log;

class FieldTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fieldTypes = [
            [
                'name' => 'Text',
                'slug' => 'text',
                'config' => [
                    'has_min_max_length' => true,
                ],
            ],
            [
                'name' => 'Textarea',
                'slug' => 'textarea',
                'config' => [
                    'has_min_max_length' => true,
                    'has_rows' => true,
                ],
            ],
            [
                'name' => 'Rich Text',
                'slug' => 'rich_text',
                'config' => [
                    'has_min_max_length' => true,
                ],
            ],
            [
                'name' => 'Number',
                'slug' => 'number',
                'config' => [
                    'has_min_max' => true,
                    'has_step' => true,
                ],
            ],
            [
                'name' => 'Date',
                'slug' => 'date',
                'config' => [
                    'has_min_max' => true,
                ],
            ],
            [
                'name' => 'Select',
                'slug' => 'select',
                'config' => [
                    'has_options' => true,
                ],
            ],
            [
                'name' => 'Checkbox',
                'slug' => 'checkbox',
                'config' => [],
            ],
            [
                'name' => 'File',
                'slug' => 'file',
                'config' => [
                    'has_file_options' => true,
                ],
            ],
        ];

        foreach ($fieldTypes as $fieldType) {
            $createdFieldType = FieldType::updateOrCreate(
                ['slug' => $fieldType['slug']],
                [
                    'name' => $fieldType['name'],
                    'config' => $fieldType['config'],
                ]
            );
            Log::info("Created/Updated Field Type: " . $createdFieldType->name . " with config: " . json_encode($createdFieldType->config));
        }
    }
}
