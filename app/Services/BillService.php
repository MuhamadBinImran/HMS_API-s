<?php

namespace App\Services;

use App\DTOs\BillDTO;
use App\Models\Bill;

class BillService
{
    /**
     * Create and store a new bill using the given DTO.
     */
    public function createBill(BillDTO $dto): Bill
    {
        return Bill::create([
            'patient_id'        => $dto->patient_id,
            'appointment_id'    => $dto->appointment_id,
            'consultation_fee'  => $dto->consultation_fee,
            'lab_tests_fee'     => $dto->lab_tests_fee,
            'medicine_fee'      => $dto->medicine_fee,
            'total_amount'      => $dto->total_amount ?? (
                    $dto->consultation_fee + $dto->lab_tests_fee + $dto->medicine_fee
                ),
            'status'            => $dto->status ?? 'unpaid',
        ]);
    }

    /**
     * Update the status of the given bill (e.g., mark as paid).
     */
    public function updateStatus(Bill $bill, string $status): Bill
    {
        $bill->status = $status;
        $bill->save();

        return $bill;
    }
}
