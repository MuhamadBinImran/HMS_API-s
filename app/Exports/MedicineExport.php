<?php

namespace App\Exports;

use App\Models\Medicine;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MedicineExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    public function __construct()
    {
        // ✅ Only fetch non-deleted medicines
        $this->data = Medicine::withoutTrashed()->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'Quantity',
            'Expiry Date',
            'Description',
        ];
    }

    public function map($medicine): array
    {
        return [
            $medicine->id,
            $medicine->name,
            $medicine->category,
            $medicine->quantity,
            $medicine->expiry_date,
            $medicine->description,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [];
        $now = Carbon::now();

        foreach ($this->data as $index => $medicine) {
            $row = $index + 2; // Header is row 1

            if ($medicine->expiry_date < $now->toDateString()) {
                // Mark expired (red)
                $styles["A$row:F$row"] = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FF9999'],
                    ],
                ];
            } elseif ($medicine->quantity < 10) {
                // Mark low stock (yellow)
                $styles["A$row:F$row"] = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFF99'],
                    ],
                ];
            }
        }

        return $styles;
    }
}
