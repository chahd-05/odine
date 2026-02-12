<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Administrator with full access']
        );

        $editorRole = Role::firstOrCreate(
            ['slug' => 'editor'],
            ['name' => 'Editor', 'description' => 'Editor can manage own resources']
        );

        $viewerRole = Role::firstOrCreate(
            ['slug' => 'viewer'],
            ['name' => 'Viewer', 'description' => 'Viewer can only read']
        );

        $admin = User::withTrashed()->updateOrCreate(
            ['email' => 'admin@odin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $editor = User::withTrashed()->updateOrCreate(
            ['email' => 'editor@odin.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $editor->roles()->syncWithoutDetaching([$editorRole->id]);

        $viewer = User::withTrashed()->updateOrCreate(
            ['email' => 'viewer@odin.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $viewer->roles()->syncWithoutDetaching([$viewerRole->id]);
    }
}
