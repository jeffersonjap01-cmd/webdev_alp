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
        // Get vet users
        $vet1 = User::where('email', 'vet@vetcare.com')->first();
        $vet2 = User::where('email', 'vet2@vetcare.com')->first();

        if ($vet1) {
            Doctor::create([
                'user_id' => $vet1->id,
                'name' => 'Dr. Ahmad Rizki',
                'email' => 'vet@vetcare.com',
                'phone' => '081234567890',
                'specialization' => 'General Veterinary Medicine',
                'is_active' => true,
            ]);
        }

        if ($vet2) {
            Doctor::create([
                'user_id' => $vet2->id,
                'name' => 'Dr. Sari Indrawati',
                'email' => 'vet2@vetcare.com',
                'phone' => '081234567891',
                'specialization' => 'Small Animal Surgery',
                'is_active' => true,
            ]);
        }
    }
}
