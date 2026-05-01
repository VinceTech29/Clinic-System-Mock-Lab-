<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialization',
        'consultation_fee',
        'license_number',
        'bio',
        'status',
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
    ];

    // Relationships
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Get available time slots
    public function getAvailableSlots($date)
    {
        $bookedTimes = $this->appointments()
            ->whereDate('appointment_date_time', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_date_time')
            ->toArray();

        $availableSlots = [];
        for ($hour = 9; $hour < 17; $hour++) {
            for ($min = 0; $min < 60; $min += 30) {
                $time = sprintf('%02d:%02d', $hour, $min);
                $dateTime = "$date $time";
                if (!in_array($dateTime, $bookedTimes)) {
                    $availableSlots[] = $time;
                }
            }
        }
        return $availableSlots;
    }
}
