<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PDF;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
class PDFController extends Controller
{

    public function survey_report($barangay)
    {

        $data = DB::table('individual_lifecycle_risks')
        ->select(DB::raw('sum(pregnancy_and_birth=1) as k1'), DB::raw('sum(pregnancy_and_birth=2) as k2'), DB::raw('sum(pregnancy_and_birth=3) as k3'))
        ->join('respondents_information', 'individual_lifecycle_risks.information_id', '=', 'respondents_information.id')
        ->where('respondents_information.barangay', '=', $barangay)
        ->get();

        $users = User::get();

        $data = [
            'title' => 'City Government of Malolos - Business Itinerary ',
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = PDF::loadView('survey', $data);
        return $pdf->stream();
    }
}
