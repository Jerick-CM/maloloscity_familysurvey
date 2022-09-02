<?php

namespace App\Exports;

use App\Models\Itinerary;
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

class ItineraryExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;
    public $count;
    public function collection()
    {
        return Itinerary::all();
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
            "No.", "Control Number", "Itinerary Name", "Request Date",  "Job Start",  "Job End",  "Completion Time", "Date / Time",
        ];
    }

    public function map($data): array
    {

        $this->count++;
        return [
            $this->count,
            $data->control_number,
            $data->name,
            $data->requestdate,
            $data->start_at,
            $data->end_at,
            $data->completed_time,
            Carbon::parse($data->created_at)->isoFormat('HH:mm - MMM Do YYYY '),
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
