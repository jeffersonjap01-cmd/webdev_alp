<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'doctor_id',
        'date',
        'diagnosis',
        'treatment',
        'notes',
        'temperature',
        'heart_rate',
    ];

    protected $casts = [
        'date' => 'date',
        'temperature' => 'decimal:2',
        'heart_rate' => 'integer',
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
