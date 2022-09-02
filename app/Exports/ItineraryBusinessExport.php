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

class ItineraryBusinessExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;
    public $count;
    public function collection()
    {
        // return Itinerary::all();

        return  Itinerary::select(
            'businesses.*',

            'itineraries.id as i_id',
            'itineraries.control_number as i_control_number',
            'itineraries.name as i_name',
            'itineraries.requestdate as i_requestdate',
            'itineraries.completed_time as i_completed_time',
            'itineraries.start_at as i_start_at',
            'itineraries.end_at as i_end_at',

            'itinerary_businesses.id as ib_busi_id',
            'itinerary_businesses.remarks as ib_remarks',
            'itinerary_businesses.status as ib_status',
        )
            ->join('itinerary_businesses', 'itineraries.id', '=', 'itinerary_businesses.itinerary_id')
            ->join('businesses', 'businesses.id', '=', 'itinerary_businesses.business_id')
            ->whereNull('itinerary_businesses.deleted_at')
            ->get();
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
            "No.", "Control Number", "Itinerary Name", "Request Date",  "Job Start",  "Job End",  "Completion Time", "Business name", "Checklist Remarks", "Checklist Status", "Date / Time",
        ];
    }

    public function map($data): array
    {

        $this->count++;
        return [
            $this->count,
            $data->i_control_number,
            $data->i_name,
            $data->i_requestdate,
            $data->i_start_at,
            $data->i_end_at,
            $data->i_completed_time,
            $data->business_name,
            $data->ib_remarks,
            $data->ib_status ? 'Done' : 'Pending',
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
                $sheet->getStyle('B2:B100')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            }

        ];
    }
}
