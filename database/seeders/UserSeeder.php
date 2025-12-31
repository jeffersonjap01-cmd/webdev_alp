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
        User::firstOrCreate(
            ['email' => 'admin@vetcare.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create doctor users
        User::firstOrCreate(
            ['email' => 'doctor@vetcare.com'],
            [
                'name' => 'Dr. Ahmad Rizki',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'doctor2@vetcare.com'],
            [
                'name' => 'Dr. Sari Indrawati',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]
        );

        // Create customer users (these emails are used by other seeders)
        User::firstOrCreate(
            ['email' => 'customer@vetcare.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer2@vetcare.com'],
            [
                'name' => 'Siti Sarah',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer3@vetcare.com'],
            [
                'name' => 'Ahmad Wijaya',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // Create test user
        User::firstOrCreate(
            ['email' => 'mk@gmail.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
    }
}
