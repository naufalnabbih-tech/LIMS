<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $userRole = Role::where('name', 'user')->first();

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@lims.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $adminRole?->id,
        ]);

        // Create test user (manager role)
        User::create([
            'name' => 'Test User',
            'email' => 'test@lims.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $managerRole?->id,
        ]);

        // Create lab technician (user role)
        User::create([
            'name' => 'Lab Technician',
            'email' => 'lab@lims.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $userRole?->id,
        ]);
    }
}
