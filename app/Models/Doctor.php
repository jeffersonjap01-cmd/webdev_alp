<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'specialization',
        'is_active',
        'service_duration',
    ];

    // Relasi ke user (login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Dokter menangani banyak appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
