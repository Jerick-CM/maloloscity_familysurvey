<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;


use Maatwebsite\Excel\Concerns\FromCollection;
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
use Carbon\Carbon;

use App\Models\Itinerary;

class ItineraryBusinessSelectedExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;
    public $count;
    public function __construct($items)
    {
        $this->items = $items;
    }

    public function array(): array
    {

        $applications = $this->items;
        $data = [];

        foreach ($applications as $application) {

            $itinerary = Itinerary::select(
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

                'itinerary_businesses.start_at as ib_start_at',
                'itinerary_businesses.end_at as ib_end_at',
                'itinerary_businesses.completed_time as ib_completed_time',
                'itinerary_businesses.remarks as ib_remarks',

                'users.name as username',
            )
                ->join('itinerary_businesses', 'itineraries.id', '=', 'itinerary_businesses.itinerary_id')
                ->join('businesses', 'businesses.id', '=', 'itinerary_businesses.business_id')
                ->join('users', 'users.id', '=', 'itineraries.assign_id')
                ->whereNull('itinerary_businesses.deleted_at')
                ->where('itineraries.id', $application['id'])
                ->get();



            foreach ($itinerary as $ky => $vl) {
                $this->count++;

                $data[] =  [
                    $this->count,
                    $vl['i_control_number'],
                    $vl['i_name'],

                    $vl['business_identification_number'],
                    $vl['business_name'],
                    $vl['address'],
                    $vl['line_of_business'],
                    $vl['ib_start_at'],
                    $vl['ib_end_at'],
                    $vl['ib_completed_time'],
                    $vl['ib_remarks'],
                    $vl['username'],
                    $vl['ib_status'] == 1 ? 'Done' : 'Pending',
                    $vl['i_requestdate'],
                ];
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Control Number',
            'Itinerary Name',

            'B.I.N.',
            'Business Name',
            'Address',
            'Line of Business',

            'Time Start',
            'Time End',
            'Completed Time',
            'Remarks',
            'Assigned To',
            'Completed Status',
            'Date / Time',
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
