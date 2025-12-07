<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'pet_id',
        'doctor_id',
        'appointment_time',
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

    public function owner()
    {
        return $this->hasOneThrough(Owner::class, Pet::class, 'id', 'id', 'pet_id', 'customer_id');
    }

    // Scopes
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
