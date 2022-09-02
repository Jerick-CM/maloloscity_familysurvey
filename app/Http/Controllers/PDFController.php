<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PDF;
use App\Models\Itinerary;
use Carbon\Carbon;
use DateTime;
use App\Models\Business;
use App\Models\ItineraryBusiness;
use App\Models\ChecklistData;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $users = User::get();

        $data = [
            'title' => 'City Government of Malolos - Business Itinerary ',
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = PDF::loadView('myPDF', $data);
        return $pdf->stream();
    }

    public function view_print_itinerary($id)
    {

        $itinerary = Itinerary::where('id', $id)->get();
        $business = Business::select('id as value', 'business_name as label', 'business_name', 'address', 'line_of_business', 'owner', 'capital', 'contact_number', 'business_identification_number as bin')
            ->whereIn('id', explode(",", $itinerary[0]->businesses))->get();
        $data = [
            'title' => 'City Government of Malolos - Business Itinerary ',
            'date' => Carbon::parse(date('m/d/Y'))->dayName . " " . Carbon::parse(date('m/d/Y'))->isoFormat(', MMM Do YYYY '),
            'data' => $itinerary,
            'qr' => $itinerary[0]->qr_filename,
            'range' =>  $this->hoursRange(28800, 61200, 60 * 30, 'h:i a'),
            'business' => $business
        ];

        $pdf = PDF::loadView('itinerary', $data);
        return $pdf->stream();
    }


    function hoursRange($lower = 0, $upper = 86400, $step = 3600, $format = '')
    {
        $times = array();

        if (empty($format)) {
            $format = 'g:i a';
        }
        $count = 0;

        foreach (range($lower, $upper, $step) as $increment) {

            $increment = gmdate('H:i', $increment);

            list($hour, $minutes) = explode(':', $increment);

            $date = new DateTime($hour . ':' . $minutes);

            // $times[(string) $increment] = $date->format($format);
            if ($count == 8 || $count == 9) {
            }

            $times[$count] = $date->format($format);
            $count = $count + 1;
        }

        return $times;
    }

    public function view_print_checklist($id)
    {

        $itinerary = Itinerary::where('id', $id)->get();
        $business = Business::select('id as value', 'business_name as label', 'business_name', 'address', 'line_of_business', 'owner', 'capital', 'contact_number', 'business_identification_number as bin')
            ->whereIn('id', explode(",", $itinerary[0]->businesses))->get();


        $itinerary_business = ItineraryBusiness::where('itinerary_id', $id)
            ->select("businesses.business_name", "businesses.address", "itinerary_businesses.*", 'business_name', 'address', 'line_of_business', 'owner', 'capital', 'contact_number', 'business_identification_number as bin')
            ->join('businesses', 'businesses.id', '=', 'itinerary_businesses.business_id')
            ->get();

        $data = [
            'title' => 'City Government of Malolos - Business Itinerary ',
            'date' => Carbon::parse(date('m/d/Y'))->dayName . " " . Carbon::parse(date('m/d/Y'))->isoFormat(', MMM Do YYYY '),
            'data' => $itinerary,
            'qr' => $itinerary[0]->qr_filename,
            'range' =>  $this->hoursRange(28800, 61200, 60 * 30, 'h:i a'),
            'business' => $business,
            'pivot' => $itinerary_business
        ];

        $pdf = PDF::loadView('checklist', $data);
        return $pdf->stream();
    }
}
