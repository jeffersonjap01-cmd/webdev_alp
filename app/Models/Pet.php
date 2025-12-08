<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'name',
        'species',
        'breed',
        'age',
        'weight',
        'color',
        'gender',
        'photo_url',
    ];

    protected $casts = [
        'age' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Alias for customer relationship (for backward compatibility)
    public function owner()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Accessors
    public function getPhotoAttribute(): ?string
    {
        return $this->photo_url;
    }

    public function getAgeInMonthsAttribute(): float
    {
        return $this->age * 12;
    }

    // Scopes
    public function scopeBySpecies($query, $species)
    {
        return $query->where('species', $species);
    }

    public function scopeByOwner($query, $ownerId)
    {
        return $query->where('customer_id', $ownerId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('breed', 'like', "%{$search}%")
              ->orWhere('species', 'like', "%{$search}%");
        });
    }
}
