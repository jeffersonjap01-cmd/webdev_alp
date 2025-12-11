<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'pet_id',
        'doctor_id',
        'appointment_time',
        'service_type',
        'duration',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function vaccination()
    {
        return $this->hasOne(Vaccination::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_time', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_time', '>=', now())
                      ->where('status', 'pending');
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByPet($query, $petId)
    {
        return $query->where('pet_id', $petId);
    }

    // Methods
    public function accept()
    {
        $this->status = 'accepted';
        $this->save();
        return $this;
    }

    public function decline()
    {
        $this->status = 'declined';
        $this->save();
        return $this;
    }

    public function startProgress()
    {
        $this->status = 'in_progress';
        $this->save();
        return $this;
    }

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
