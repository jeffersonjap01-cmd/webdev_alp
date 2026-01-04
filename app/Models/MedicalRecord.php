<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'pet_id',
        'doctor_id',
        'symptoms',
        'notes',
        'recommendation',
        'record_date',
    ];

    protected $casts = [
        'record_date' => 'datetime',
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

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Scopes
    public function scopeByPet($query, $petId)
    {
        return $query->where('pet_id', $petId);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    public function scopeOrderByDate($query, $direction = 'desc')
    {
        return $query->orderBy('date', $direction);
    }
}
