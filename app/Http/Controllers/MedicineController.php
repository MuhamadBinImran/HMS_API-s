<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MedicineExport;

class MedicineController extends Controller
{
    // 🟢 GET /medicines – List all medicines
    public function index()
    {
        $medicines = Medicine::all();
        return response()->json(['success' => true, 'data' => $medicines]);
    }

    // 🟢 POST /medicines – Add a new medicine
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'quantity'    => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $medicine = Medicine::create($validated);
        return response()->json(['success' => true, 'data' => $medicine]);
    }

    // 🟢 GET /medicines/{id} – View a specific medicine
    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);
        return response()->json(['success' => true, 'data' => $medicine]);
    }

    // 🟢 PUT /medicines/{id} – Update a medicine
    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'category'    => 'sometimes|required|string|max:255',
            'quantity'    => 'sometimes|required|integer|min:0',
            'expiry_date' => 'sometimes|required|date',
            'description' => 'nullable|string',
        ]);

        $medicine->update($validated);
        return response()->json(['success' => true, 'data' => $medicine]);
    }

    // 🟢 DELETE /medicines/{id} – Soft-delete a medicine
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return response()->json(['success' => true, 'message' => 'Medicine deleted successfully']);
    }

    // 🟢 GET /medicines/export – Export to Excel
    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MedicineExport(), 'medicines.xlsx');
    }


}
