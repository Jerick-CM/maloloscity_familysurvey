<?php

namespace App\Exports;

use App\Models\User;

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


class UsersExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    use Exportable;
    public $count;

    public function collection()
    {
        return User::select('name', 'email', 'created_at')->get();
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
        return ["No", "Name", "E-mail",  "Data / Time"];
    }

    public function map($user): array
    {




        $this->count++;
        return [
            $this->count,
            $user->name,
            $user->email,
            Carbon::parse($user->created_at)->isoFormat('HH:mm - MMM Do YYYY '),
        ];
    }
}
