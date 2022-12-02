<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PWD;
use App\Models\Pwd_renewal;
use Carbon\Carbon;

class PwdRenewalYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pwds = PWD::select('id', 'date_applied')->get();


        foreach ($pwds as $pwd) {
            if ($pwd->date_applied) {
                $year = Carbon::createFromFormat('Y-m-d', $pwd->date_applied)->format('Y');
                try {
                    Pwd_renewal::create([
                        'year' => $year,
                        'pwd_id' => $pwd->id,
                        'date_of_application' => $pwd->date_applied,
                    ]);
                } catch (Throwable $e) {
                    // report($e);

                    return false;
                }
            }
        }
    }
}
