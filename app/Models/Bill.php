<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Appointment;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'consultation_fee',
        'lab_tests_fee',
        'medicine_fee',
        'total_amount',
        'status',
    ];

    // Corrected Relationship
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
