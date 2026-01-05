<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'appointment_id',
        'user_id',
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
        'qr_code_image',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function medicalRecord()
    {
        return $this->hasOneThrough(
            MedicalRecord::class,
            Appointment::class,
            'id', // Foreign key on appointments table
            'appointment_id', // Foreign key on medical_records table
            'appointment_id', // Local key on invoices table
            'id' // Local key on appointments table
        );
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