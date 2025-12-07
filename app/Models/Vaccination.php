<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

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