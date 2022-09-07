<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RespondentsInformation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $respondents_count = RespondentsInformation::whereNull('respondents_information.deleted_at')->count();
        $respondents_per_barangay =  DB::select("SELECT count(*) as y, barangay as 'name' FROM `respondents_information` where deleted_at IS NULL GROUP BY barangay  ORDER BY y DESC;");
        $top10barangay =  DB::select("SELECT count(*) as y, barangay as 'name' FROM `respondents_information` where deleted_at IS NULL GROUP BY barangay  ORDER BY y DESC Limit 10;");

        $fourPs_member = RespondentsInformation::where('four_ps_beneficiary', 1)->whereNull('respondents_information.deleted_at')->count();
        $fourPs_non_member = RespondentsInformation::where('four_ps_beneficiary', 0)->whereNull('respondents_information.deleted_at')->count();


        return response()->json([
            'data' => [
                'total_respondents' => $respondents_count,
                'respondents_per_barangay' => $respondents_per_barangay,
                'total_respondents_chart' =>  [$respondents_count],
                'top10barangay' => $top10barangay,
                'four4Ps_non4Ps' => [['y' => $fourPs_member, 'name' => 'member'], ['y' => $fourPs_non_member, 'name' => 'non-member']],
                'fourPs' => $fourPs_member,
                'nonfourPs' => $fourPs_non_member,
            ]
        ]);
    }
}
