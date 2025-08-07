<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class BillDTO
{
    public int $patient_id;
    public int $appointment_id;
    public float $consultation_fee;
    public float $lab_tests_fee;
    public float $medicine_fee;
    public float $total_amount;
    public string $status;

    public function __construct(
        int $patient_id,
        int $appointment_id,
        float $consultation_fee,
        float $lab_tests_fee,
        float $medicine_fee,
        float $total_amount,
        string $status
    ) {
        $this->patient_id       = $patient_id;
        $this->appointment_id   = $appointment_id;
        $this->consultation_fee = $consultation_fee;
        $this->lab_tests_fee    = $lab_tests_fee;
        $this->medicine_fee     = $medicine_fee;
        $this->total_amount     = $total_amount;
        $this->status           = $status;
    }

    public static function fromRequest(Request $request): self
    {
        $total = $request->consultation_fee + $request->lab_tests_fee + $request->medicine_fee;

        return new self(
            $request->patient_id,
            $request->appointment_id,
            $request->consultation_fee,
            $request->lab_tests_fee,
            $request->medicine_fee,
            $total,
            $request->status ?? 'unpaid'
        );
    }
}
