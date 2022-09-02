<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;


class PullExport implements FromArray, WithHeadings, ShouldAutoSize
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
            $this->count++;
            $data[] =  [
                $this->count,
                $application['business_identification_number'],
                $application['business_name'],
                $application['line_of_business'],
                $application['start_at'],
                $application['end_at'],
                $application['completed_time'],
                $application['remarks'],
                $application['username'],
                $application['i_stat'] == 1 ? 'Done' : 'Pending',
                $application['created_date'],
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No.',
            'B.I.N',
            'Business Name',
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
}
