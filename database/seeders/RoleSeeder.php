<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => [
                    'manage_users',
                    'manage_roles',
                    'manage_raw_materials',
                    'manage_categories',
                    'manage_specifications',
                    'manage_references',
                    'manage_samples',
                    'view_reports',
                    'manage_settings',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Management access with most permissions',
                'permissions' => [
                    'manage_raw_materials',
                    'manage_categories',
                    'manage_specifications',
                    'manage_references',
                    'manage_samples',
                    'view_reports',
                    'view_users',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Basic user access for daily operations',
                'permissions' => [
                    'view_raw_materials',
                    'view_categories',
                    'view_specifications',
                    'view_references',
                    'manage_samples',
                    'view_reports',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
