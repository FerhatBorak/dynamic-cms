<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $editorRole = Role::create(['name' => 'editor']);

        // İzinler
        $createContent = Permission::create(['name' => 'create content']);
        $editContent = Permission::create(['name' => 'edit content']);
        $deleteContent = Permission::create(['name' => 'delete content']);
        $manageCategories = Permission::create(['name' => 'manage categories']);

        // Rollere izin atama
        $editorRole->givePermissionTo([$createContent, $editContent, $deleteContent]);
        $superAdminRole->givePermissionTo(Permission::all());

        // Örnek kullanıcılar oluşturma
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'info@ferhatborak.com',
            'password' => bcrypt('Ferhat.56'),
        ]);
        $superAdmin->assignRole('super-admin');

        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
        ]);
        $editor->assignRole('editor');

        $this->call([
            FieldTypeSeeder::class,
            LanguageSeeder::class,
        ]);
    }
}

