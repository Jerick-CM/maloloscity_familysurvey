<?php

namespace App\Exports;

use App\Models\Business;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class BusinessExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{
    use Exportable;
    public $count;

    public function collection()
    {
        // return Business::limit(10)->get();
        return Business::get();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 20,
            'C' => 34,
            'D' => 30,
            'E' => 40,
            'F' => 15,
        ];
    }
    public function headings(): array
    {
        return [
            "No.", "BIN", "Business Name", "Line of business",  "Owner",  "Address",  "Barangay", "Code",
            "Gross Receipts", 'Capital', 'Permit Number', 'Business Tax', 'Fees',
            'Surcharge', 'Total', 'Official Receipt Number', 'Official Receipt Date', 'Terms',
            'Tax Year', 'Qtr Paid', 'Contact Number', 'Number of Employees', 'Status'
        ];
    }

    public function map($data): array
    {

        $this->count++;
        return [
            $this->count,
            $data->business_identification_number,
            $data->business_name,

            $data->line_of_business,
            $data->owner,
            $data->address,
            $data->barangay,
            $data->code,
            $data->gross_receipts,
            $data->capital,
            $data->permit_number,
            $data->business_tax,

            $data->fees,
            $data->surcharge,
            $data->total,

            $data->official_receipt_number,
            $data->official_receipt_date,
            $data->terms,
            $data->tax_year,
            $data->qtr_paid,
            $data->contact_number,
            $data->number_of_employees,
            $data->status,

            // Carbon::parse($data->created_at)->isoFormat('HH:mm - MMM Do YYYY '),
        ];
    }

    public function registerEvents(): array
    {
        $Headings = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];

        $Border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        return [
            AfterSheet::class    => function (AfterSheet $event) use ($Headings) {
                // All headers
                $sheet = $event->sheet;
                $cellRange = 'A1:K1';
                $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($Headings)->getFont()->setSize(12);

                // All Data
                $sheet->getStyle('B2:B100')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            }

        ];
    }
}
