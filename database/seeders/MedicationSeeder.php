<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medication;
use App\Models\Prescription;

class MedicationSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $prescriptions = Prescription::take(12)->get();

        $sample = [
            ['name' => 'Amoxicillin', 'dosage' => '250 mg', 'frequency' => '2x sehari'],
            ['name' => 'Carprofen', 'dosage' => '50 mg', 'frequency' => '1x sehari'],
            ['name' => 'Metronidazole', 'dosage' => '125 mg', 'frequency' => '2x sehari'],
            ['name' => 'Enrofloxacin', 'dosage' => '100 mg', 'frequency' => '1x sehari'],
            ['name' => 'Praziquantel', 'dosage' => '50 mg', 'frequency' => '1x hari'],
        ];

        foreach ($prescriptions as $prescription) {
            $item = $sample[array_rand($sample)];

            Medication::firstOrCreate([
                'prescription_id' => $prescription->id,
                'medical_record_id' => $prescription->medical_record_id,
                'medicine_name' => $item['name'],
            ], [
                'dosage' => $item['dosage'],
                'frequency' => $item['frequency'],
                'duration' => '7 hari',
            ]);
        }
    }
}
