<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
        [
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'full access to all features'
        ],
        [
            'name' => 'Editor',
            'slug' => 'editor',
            'description' => 'can manage only this own resources'
        ],
        [
            'name' => 'Viewer',
            'slug' => 'viewer',
            'description' => 'Read only no modification'
        ]
        ];

        foreach ($roles as $role){
            Role::create($role);
        }
        $this->command->info('3 roles created successfully');
    }
}
