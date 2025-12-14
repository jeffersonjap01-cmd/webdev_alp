<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Diagnosis;
use App\Models\MedicalRecord;

class DiagnosisSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $records = MedicalRecord::take(12)->get();

        foreach ($records as $record) {
            Diagnosis::firstOrCreate([
                'medical_record_id' => $record->id,
                'diagnosis_name' => 'General Checkup - ' . ($record->pet->name ?? 'hewan'),
            ], [
                'description' => 'Diagnosis awal: pemeriksaan umum, tidak ada temuan kritis',
            ]);
        }
    }
}
