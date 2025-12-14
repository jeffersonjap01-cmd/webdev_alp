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
            ['email' => 'admin@doctorcare.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create doctor users
        User::firstOrCreate(
            ['email' => 'doctor@doctorcare.com'],
            [
                'name' => 'Dr. Ahmad Rizki',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'doctor2@doctorcare.com'],
            [
                'name' => 'Dr. Sari Indrawati',
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]
        );

<<<<<<< HEAD
        // Create custome users
        User::firstOrCreate(
            ['email' => 'customer@doctorcare.com'],
=======
        // Create customer users
        User::firstOrCreate(
            ['email' => 'customer@vetcare.com'],
>>>>>>> Medication
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        User::firstOrCreate(
<<<<<<< HEAD
            ['email' => 'customer2@doctorcare.com'],
=======
            ['email' => 'customer2@vetcare.com'],
>>>>>>> Medication
            [
                'name' => 'Siti Sarah',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        User::firstOrCreate(
<<<<<<< HEAD
            User::firstOrCreate(
                ['email' => 'customer@vetcare.com'],
            ['email' => 'customer3@vetcare.com'],
>>>>>>> Medication
            [
                'name' => 'Ahmad Wijaya',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
                ['email' => 'customer2@vetcare.com'],
        // Create test user for mk@gmail.com
        User::firstOrCreate(
            ['email' => 'mk@gmail.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
                ['email' => 'customer3@vetcare.com'],
    }
}
