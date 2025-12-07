# Laravel Models & Relationships - VetCare

## 📁 Models dengan Relationships

### 1. User Model
```php
<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'reference_id',
        'reference_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVet(): bool
    {
        return $this->role === 'vet';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }
}
```

---

### 2. Owner Model
```php
<?php
// app/Models/Owner.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'registered_date',
    ];

    protected $casts = [
        'registered_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Pet::class);
    }

    // Accessors & Mutators
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->whereNull('deleted_at');
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
```

---

### 3. Pet Model
```php
<?php
// app/Models/Pet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
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
    public function owner()
    {
        return $this->belongsTo(Owner::class);
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
        return $query->where('owner_id', $ownerId);
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
```

---

### 4. Doctor Model
```php
<?php
// app/Models/Doctor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'specialization',
        'status',
        'email',
        'phone',
        'photo_url',
        'bio',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // Accessors
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getPhotoAttribute(): ?string
    {
        return $this->photo_url;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('specialization', 'like', "%{$search}%");
        });
    }

    // Methods
    public function toggleStatus()
    {
        $this->status = $this->status === 'active' ? 'inactive' : 'active';
        $this->save();
        return $this;
    }
}
```

---

### 5. MedicalRecord Model
```php
<?php
// app/Models/MedicalRecord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

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
```

---

### 6. Appointment Model
```php
<?php
// app/Models/Appointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pet_id',
        'doctor_id',
        'date',
        'time',
        'reason',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
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
        return $this->hasOneThrough(Owner::class, Pet::class, 'id', 'id', 'pet_id', 'owner_id');
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
        return $query->whereDate('date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today())
                     ->where('status', 'scheduled');
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
```

---

### 7. Invoice Model
```php
<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'owner_id',
        'pet_id',
        'date',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_reference',
        'paid_at',
    ];

    protected $casts = [
        'date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                     ->whereYear('date', now()->year);
    }

    // Methods
    public function markAsPaid($paymentMethod, $paymentReference = null)
    {
        $this->status = 'paid';
        $this->payment_method = $paymentMethod;
        $this->payment_reference = $paymentReference;
        $this->paid_at = now();
        $this->save();
        return $this;
    }

    public function calculateTotal()
    {
        $this->subtotal = $this->items()->sum('total');
        $this->total = $this->subtotal + $this->tax - $this->discount;
        $this->save();
        return $this;
    }

    // Auto-generate invoice number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $invoice->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(
                    Invoice::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
```

---

### 8. InvoiceItem Model
```php
<?php
// app/Models/InvoiceItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Auto-calculate total
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total = $item->quantity * $item->price;
        });

        static::saved(function ($item) {
            $item->invoice->calculateTotal();
        });

        static::deleted(function ($item) {
            $item->invoice->calculateTotal();
        });
    }
}
```

---

### 9. Vaccination Model
```php
<?php
// app/Models/Vaccination.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaccination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pet_id',
        'vaccine_name',
        'last_date',
        'next_date',
        'notes',
        'status',
        'reminder_sent',
    ];

    protected $casts = [
        'last_date' => 'date',
        'next_date' => 'date',
        'reminder_sent' => 'boolean',
    ];

    // Relationships
    public function pet()
    {
        return $this->belongsTo(Pet::class);
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

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('next_date', '<=', now()->addDays($days))
                     ->where('next_date', '>=', today())
                     ->where('status', 'scheduled');
    }

    public function scopeNeedsReminder($query)
    {
        return $query->where('reminder_sent', false)
                     ->where('next_date', '<=', now()->addDays(7))
                     ->where('status', 'scheduled');
    }

    // Methods
    public function complete()
    {
        $this->status = 'completed';
        $this->save();
        return $this;
    }

    public function markReminderSent()
    {
        $this->reminder_sent = true;
        $this->save();
        return $this;
    }

    // Auto-update status based on date
    public function updateStatus()
    {
        if ($this->status !== 'completed') {
            if ($this->next_date < today()) {
                $this->status = 'overdue';
            } else {
                $this->status = 'scheduled';
            }
            $this->save();
        }
        return $this;
    }
}
```

---

### 10. Prescription Model
```php
<?php
// app/Models/Prescription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

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
```

---

### 11. Medication Model
```php
<?php
// app/Models/Medication.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'name',
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
}
```

---

## 🎯 Cara Membuat Models

```bash
# Generate all models with migration
php artisan make:model Owner -m
php artisan make:model Pet -m
php artisan make:model Doctor -m
php artisan make:model MedicalRecord -m
php artisan make:model Appointment -m
php artisan make:model Invoice -m
php artisan make:model InvoiceItem -m
php artisan make:model Vaccination -m
php artisan make:model Prescription -m
php artisan make:model Medication -m

# Generate with factory & seeder
php artisan make:model Pet -mfs
```

---

## 📈 Relationship Summary

```
User (1) → (1) Owner
User (1) → (1) Doctor

Owner (1) → (*) Pet
Owner (1) → (*) Invoice

Pet (1) → (*) MedicalRecord
Pet (1) → (*) Appointment
Pet (1) → (*) Vaccination
Pet (1) → (*) Prescription
Pet (1) → (*) Invoice

Doctor (1) → (*) MedicalRecord
Doctor (1) → (*) Appointment
Doctor (1) → (*) Prescription

Invoice (1) → (*) InvoiceItem

Prescription (1) → (*) Medication
Prescription (1) → (1) MedicalRecord [optional]
```

---

Lanjut ke **CONTROLLERS.md** untuk implementasi controller! 🚀
