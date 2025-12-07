<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'doctor_id',
        'medical_record_id',
        'date',
        'diagnosis',
        'instructions',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByPet($query, $petId)
    {
        return $query->where('pet_id', $petId);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    // Methods
    public function complete()
    {
        $this->status = 'completed';
        $this->save();
        return $this;
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
        return $this;
    }
}