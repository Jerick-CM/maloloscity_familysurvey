<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;

use Carbon\Carbon;


class ISFExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;
    public $count;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function array(): array
    {
        $contents = $this->items;

        foreach ($contents as $content) {
            $this->count++;
            $data[] =  [
                $this->count,
                $content['household_head'],
                $content['birthdate'],
                $content['no_of_family_members'],
                $content['spouse_name'],
                $content['spouse_birthdate'],
                $content['tenurial_status'],
                $content['body_of_water_name'],
                $content['body_of_water_type'],
                $content['zone'],
                $content['date'],
                $content['created_at'],
            ];
        }

        return $data;
    }
    public function columnWidths(): array
    {
        return [
            'A' => 4,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
        ];
    }

    public function headings(): array
    {
        return [
            "No.",
            "Household Head",
            "Date of Birth",
            "No. of family members",
            "Spouse Name",
            "Spouse Birthdate",
            "Tenurial Status",
            "Body of water name",
            "Body of water type",
            "Zone",
            "Date",
            "Created Date",
        ];
    }
}
