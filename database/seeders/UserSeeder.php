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

        // Create vet users
        User::firstOrCreate(
            ['email' => 'vet@vetcare.com'],
            [
                'name' => 'Dr. Ahmad Rizki',
                'password' => Hash::make('password'),
                'role' => 'vet',
            ]
        );

        User::firstOrCreate(
            ['email' => 'vet2@vetcare.com'],
            [
                'name' => 'Dr. Sari Indrawati',
                'password' => Hash::make('password'),
                'role' => 'vet',
            ]
        );

        // Create owner users
        User::firstOrCreate(
            ['email' => 'owner@vetcare.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        User::firstOrCreate(
            ['email' => 'owner2@vetcare.com'],
            [
                'name' => 'Siti Sarah',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        User::firstOrCreate(
            ['email' => 'owner3@vetcare.com'],
            [
                'name' => 'Ahmad Wijaya',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        // Create test user for mk@gmail.com
        User::firstOrCreate(
            ['email' => 'mk@gmail.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );
    }
}
