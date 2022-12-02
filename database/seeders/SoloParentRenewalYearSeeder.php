<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoloParent;
use App\Models\Soloparent_renewal;
use Carbon\Carbon;

class SoloParentRenewalYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $soloparents = SoloParent::select('id', 'date_of_issuance')->get();

        foreach ($soloparents as $soloparent) {
            if ($soloparent->date_of_issuance) {

                $year = Carbon::createFromFormat('Y-m-d', $soloparent->date_of_issuance)->format('Y');
                try {
                    Soloparent_renewal::create([
                        'year' => $year,
                        'soloparent_id' => $soloparent->id,
                        'date_of_application' => $soloparent->date_of_issuance,
                    ]);
                } catch (Throwable $e) {

                    return false;
                }
            }
        }
    }
}
