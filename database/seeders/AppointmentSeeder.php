<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pets = Pet::all();
        $doctors = Doctor::all();
        $users = User::all();

        $serviceTypes = ['Pemeriksaan Umum', 'Vaksinasi', 'Pengobatan Sakit', 'Kontrol Berkala', 'Grooming'];
        $statuses = ['pending', 'scheduled', 'in_progress', 'completed', 'cancelled'];
        $durations = [30, 45, 60, 90]; // minutes

        for ($i = 0; $i < 15; $i++) {
            $pet = $pets->random();
            $doctor = $doctors->random();
            $user = $users->random();
            $serviceType = $serviceTypes[array_rand($serviceTypes)];
            $status = $statuses[array_rand($statuses)];
            $duration = $durations[array_rand($durations)];
            
            // Generate appointment time within the next 30 days
            $appointmentTime = Carbon::now()
                ->addDays(rand(1, 30))
                ->setHour(rand(8, 17))
                ->setMinute(rand(0, 1) * 30)
                ->setSecond(0);

            Appointment::firstOrCreate([
                'user_id' => $user->id,
                'pet_id' => $pet->id,
                'doctor_id' => $doctor->id,
                'appointment_time' => $appointmentTime,
            ], [
                'service_type' => $serviceType,
                'duration' => $duration,
                'status' => $status,
                'notes' => "Appointment untuk {$serviceType} - {$pet->name}",
            ]);
        }
    }
}