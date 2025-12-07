<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create vet users
        User::create([
            'name' => 'Dr. Ahmad Rizki',
            'email' => 'vet@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'vet',
        ]);

        User::create([
            'name' => 'Dr. Sari Indrawati',
            'email' => 'vet2@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'vet',
        ]);

        // Create owner users
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'owner@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Siti Sarah',
            'email' => 'owner2@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'owner3@vetcare.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Create test user for mk@gmail.com
        User::create([
            'name' => 'Test User',
            'email' => 'mk@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
    }
}
