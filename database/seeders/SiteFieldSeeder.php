<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteField;

class SiteFieldSeeder extends Seeder
{
    public function run()
    {
        $fields = [
            [
                'name' => 'title',
                'key' => 'title',
                'label' => 'Site Adı',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'phone',
                'key' => 'phone',
                'label' => 'Telefon Numarası',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'phone1',
                'key' => 'phone1',
                'label' => 'Telefon Numarası 1',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'key' => 'email',
                'label' => 'E-posta Adresi',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'address',
                'key' => 'address',
                'label' => 'Adres',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'facebook',
                'key' => 'facebook',
                'label' => 'Facebook',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'instagram',
                'key' => 'instagram',
                'label' => 'İnstagram',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'twitter',
                'key' => 'twitter',
                'label' => 'Twitter',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'youtube',
                'key' => 'youtube',
                'label' => 'Youtube',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'map',
                'key' => 'map',
                'label' => 'Google İframe',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'keyword',
                'key' => 'keyword',
                'label' => 'Meta Keywords',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'description',
                'key' => 'keywords',
                'label' => 'Meta Description',
                'column' => 6,
                'type' => 'text',
            ],
            [
                'name' => 'logo',
                'key' => 'logo',
                'label' => 'Logo',
                'column' => 6,
                'type' => 'file',
            ],
            [
                'name' => 'favicon',
                'key' => 'favicon',
                'label' => 'Favicon',
                'column' => 6,
                'type' => 'file',
            ],
        ];

        foreach ($fields as $field) {
            SiteField::updateOrCreate(['key' => $field['key']], $field);
        }
    }
}
