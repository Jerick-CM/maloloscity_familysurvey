<?php

namespace Database\Seeders;

use App\Models\SoloParent;
use Illuminate\Database\Seeder;

class BarangaySoloParentTrimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barangays = SoloParent::select('barangay')->get();

        foreach ($barangays as $barangay) {
            $barangay->barangay = trim($barangay->barangay) ;
            $barangay->save();
        }
    }
}
