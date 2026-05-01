<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'amount',
        'payment_method',
        'payment_status',
        'description',
        'remaining_balance',
        'receipt_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];

    // Relationships
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // Generate receipt number
    public static function generateReceiptNumber()
    {
        return 'RCP-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}
