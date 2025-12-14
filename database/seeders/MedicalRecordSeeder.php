<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pets = Pet::take(12)->get();
        $doctors = Doctor::all();

        foreach ($pets as $pet) {
            $doctor = $doctors->random();

            $date = Carbon::now()->subDays(rand(1, 120));

            // find existing appointment for this pet or create a minimal one
            $appointment = Appointment::where('pet_id', $pet->id)->first();
            if (! $appointment) {
                $appointment = Appointment::create([
                    'user_id' => $pet->user_id ?? null,
                    'pet_id' => $pet->id,
                    'doctor_id' => $doctor->id,
                    'appointment_time' => $date,
                    'status' => 'completed',
                ]);
            }

            MedicalRecord::firstOrCreate([
                'appointment_id' => $appointment->id,
                'pet_id' => $pet->id,
                'doctor_id' => $doctor->id,
                'record_date' => $date->toDateTimeString(),
            ], [
                'notes' => 'Rekam medis awal untuk ' . ($pet->name ?? 'hewan peliharaan'),
            ]);
        }
    }
}
