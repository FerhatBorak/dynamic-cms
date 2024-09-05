<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Models\Permission;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Rolleri oluştur
        $superAdmin = Role::create(['name' => 'super_admin', 'label' => 'Süper Admin']);
        $contentEditor = Role::create(['name' => 'content_editor', 'label' => 'İçerik Editörü']);

        // İzinleri oluştur
        $permissions = [
            'manage_categories',
            'manage_fields',
            'manage_site_fields',
            'manage_site_settings',
            'manage_languages',
            'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'label' => ucfirst(str_replace('_', ' ', $permission))]);
        }

        // Süper admin için tüm izinleri ata
        $superAdmin->permissions()->attach(Permission::all());

        // Kategorileri oluştur ve içerik editörü izinlerini ata
        $categories = ['Blog', 'Haberler', 'Ürünler'];
        foreach ($categories as $category) {
            $cat = Category::firstOrCreate(['name' => $category, 'slug' => Str::slug($category)]);
            $permission = Permission::firstOrCreate([
                'name' => 'edit_' . $cat->slug,
                'label' => 'Edit ' . $category
            ]);
            $contentEditor->permissions()->syncWithoutDetaching([$permission->id]);
        }

        // Örnek kullanıcılar oluştur
        User::create([
            'name' => 'Super Admin',
            'email' => 'info@ferhatborak.com',
            'password' => Hash::make('Ferhat.56'),
        ])->roles()->attach($superAdmin);

        User::create([
            'name' => 'Content Editor',
            'email' => 'editor@example.com',
            'password' => Hash::make('Ferhat.56'),
        ])->roles()->attach($contentEditor);
    }
}
