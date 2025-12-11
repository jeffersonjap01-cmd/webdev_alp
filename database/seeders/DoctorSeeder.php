<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'email' => 'vet@vetcare.com',
                'name' => 'Dr. Ahmad Rizki',
                'specialization' => 'General Practice',
                'phone' => '081234567890',
            ],
            [
                'email' => 'vet2@vetcare.com',
                'name' => 'Dr. Sarah Johnson',
                'specialization' => 'Surgery',
                'phone' => '081234567891',
            ],
            [
                'email' => 'vet3@vetcare.com',
                'name' => 'Dr. Budi Santoso',
                'specialization' => 'Internal Medicine',
                'phone' => '081234567892',
            ],
            [
                'email' => 'vet4@vetcare.com',
                'name' => 'Dr. Lisa Chen',
                'specialization' => 'Vaccination Specialist',
                'phone' => '081234567893',
            ],
        ];

        foreach ($doctors as $doctorData) {
            $user = User::firstOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'password' => bcrypt('password'),
                    'role' => 'doctor',
                ]
            );

            if ($user) {
                Doctor::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $doctorData['name'],
                        'email' => $doctorData['email'],
                        'specialization' => $doctorData['specialization'],
                        'phone' => $doctorData['phone'],
                        'status' => 'active', // Ensure status is 'active'
                    ]
                );
            }
        }
    }
}
