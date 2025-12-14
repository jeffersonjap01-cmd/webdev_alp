<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Models\Doctor;
use Carbon\Carbon;

class PrescriptionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $records = MedicalRecord::take(12)->get();
        $doctors = Doctor::all();

        foreach ($records as $record) {
            $doctor = $doctors->random();

            Prescription::firstOrCreate([
                'medical_record_id' => $record->id,
                'pet_id' => $record->pet_id,
                'doctor_id' => $doctor->id,
                'date' => Carbon::parse($record->record_date)->toDateString(),
            ], [
                'notes' => 'Resep standar untuk ' . ($record->pet->name ?? 'hewan'),
                'status' => 'pending',
            ]);
        }
    }
}
