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
use Maatwebsite\Excel\Concerns\FromArray;
use Carbon\Carbon;

class BusinessSelectedExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;
    public $count;

    public function __construct($items)
    {
        $this->items = $items;
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



    public function array(): array
    {

        $businesses = $this->items;
        $data = [];

        foreach ($businesses as $business) {
            $this->count++;
            $data[] =  [
                $this->count,
                $business['business_identification_number'],
                $business['business_name'],
                $business['line_of_business'],
                $business['owner'],

                $business['address'],
                $business['barangay'],
                $business['code'],
                $business['gross_receipts'],
                $business['capital'],

                $business['permit_number'],
                $business['business_tax'],
                $business['fees'],
                $business['surcharge'],
                $business['total'],

                $business['official_receipt_number'],
                $business['official_receipt_date'],
                $business['terms'],
                $business['tax_year'],
                $business['qtr_paid'],

                $business['contact_number'],
                $business['number_of_employees'],
                $business['status'],

            ];
        }

        return $data;
    }
    public function headings(): array
    {
        return [
            "No.",
            "BIN",
            "Business Name",
            "Line of business",
            "Owner",

            "Address",
            "Barangay",
            "Code",
            "Gross Receipts",
            'Capital',

            'Permit Number',
            'Business Tax',
            'Fees',
            'Surcharge',
            'Total',

            'Official Receipt Number',
            'Official Receipt Date',
            'Terms',
            'Tax Year',
            'Qtr Paid',

            'Contact Number',
            'Number of Employees',
            'Status'
        ];
    }
}
