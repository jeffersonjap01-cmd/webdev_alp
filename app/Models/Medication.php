<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medical_record_id',
        'medicine_name',
        'dosage',
        'frequency',
        'duration',
        'notes',
    ];

    // Relationships
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
