<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
    ];

    // RELATION TO USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELATION TO PETS (CUSTOMER PUNYA BANYAK PET)
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('user', function($q) {
            $q->whereNull('deleted_at');
        });
    }
}
