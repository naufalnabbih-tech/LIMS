<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@lims.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@lims.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create lab technician
        User::create([
            'name' => 'Lab Technician',
            'email' => 'lab@lims.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
