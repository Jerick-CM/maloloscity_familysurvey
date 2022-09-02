<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use App\Models\ItineraryBusiness;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $itinerary_count = ItineraryBusiness::count();
        $itinerary_business_pending = ItineraryBusiness::whereNull('completed_time')->count();
        $itinerary_business_completed = ItineraryBusiness::whereNotNull('completed_time')->count();
        $business_count = Business::count();

        $lineOfBusiness =  DB::select("SELECT count(*) as y, line_of_business as 'name' FROM `businesses` GROUP BY line_of_business  ORDER BY y DESC Limit 10;");
        $business_in_barangay =  DB::select("SELECT count(*) as y, barangay as 'name' FROM `businesses` GROUP BY barangay  ORDER BY y DESC;");

        return response()->json([
            'data' => [
                'business_barangay' => $business_in_barangay,
                'line_of_business' => $lineOfBusiness,
                'total_business' =>  [$business_count],
                'total_itinerary' => $itinerary_count,
                'pending_itinerary_business' => $itinerary_business_pending,
                'complete_itinerary_business' => $itinerary_business_completed,
                'completedVsPending' => [['y' => $itinerary_business_completed, 'name' => 'completed'], ['y' => $itinerary_business_pending, 'name' => 'pending']]
            ]
        ]);
    }
}
