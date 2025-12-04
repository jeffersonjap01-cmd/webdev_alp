<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'species',
        'breed',
        'age',
        'gender',
        'color',
    ];

    // RELASI: Pet dimiliki oleh Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relasi ke medical record
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
