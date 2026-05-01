<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date_time',
        'status',
        'notes',
        'reason_for_visit',
        'consultation_fee',
    ];

    protected $casts = [
        'appointment_date_time' => 'datetime',
        'consultation_fee' => 'decimal:2',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    // Check if appointment can be rescheduled
    public function canReschedule()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    // Check if appointment can be cancelled
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }
}
