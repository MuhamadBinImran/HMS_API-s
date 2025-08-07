<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BillService;
use App\DTOs\BillDTO;
use App\Models\Bill;

class BillController extends Controller
{
    protected BillService $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    /**
     * Store a new bill.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'        => 'required|exists:users,id',
            'appointment_id'    => 'required|exists:appointments,id',
            'consultation_fee'  => 'required|numeric|min:0',
            'lab_tests_fee'     => 'nullable|numeric|min:0',
            'medicine_fee'      => 'nullable|numeric|min:0',
            'status'            => 'required|in:unpaid,paid',
        ]);

        // Default values if null
        $validated['lab_tests_fee'] = $validated['lab_tests_fee'] ?? 0;
        $validated['medicine_fee'] = $validated['medicine_fee'] ?? 0;

        $total = $validated['consultation_fee'] + $validated['lab_tests_fee'] + $validated['medicine_fee'];
        $validated['total_amount'] = $total;

        $dto = new BillDTO(...$validated);

        $bill = $this->billService->createBill($dto);

        return response()->json([
            'message' => 'Bill created successfully.',
            'bill'    => $bill
        ], 201);
    }

    /**
     * Update the bill status (e.g., mark as paid).
     */
    public function updateStatus(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid',
        ]);

        $updatedBill = $this->billService->updateStatus($bill, $validated['status']);

        return response()->json([
            'message' => 'Bill status updated successfully.',
            'bill'    => $updatedBill
        ]);
    }

    /**
     * Get all bills (admin).
     */
    public function index()
    {
        return response()->json([
            'bills' => Bill::with(['patient', 'appointment'])->get()
        ]);
    }

    /**
     * Show a single bill (admin).
     */
    public function show(Bill $bill)
    {
        return response()->json([
            'bill' => $bill->load(['patient', 'appointment'])
        ]);
    }

    /**
     * Delete a bill (optional).
     */
    public function destroy(Bill $bill)
    {
        $bill->delete();

        return response()->json([
            'message' => 'Bill deleted successfully.'
        ]);
    }
}
