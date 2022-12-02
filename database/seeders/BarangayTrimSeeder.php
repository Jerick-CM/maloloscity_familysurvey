<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PWD;

class BarangayTrimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barangays = PWD::select('barangay')->get();

        foreach ($barangays as $barangay) {
            $barangay->barangay = trim($barangay->barangay) ;
            $barangay->save();
        }
    }
}
