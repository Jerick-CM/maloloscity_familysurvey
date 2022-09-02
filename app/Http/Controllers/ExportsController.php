<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\ItineraryExport;
use App\Exports\BusinessExport;
use App\Exports\ItineraryBusinessExport;
use App\Exports\PullExport;
use App\Exports\ItineraryBusinessSelectedExport;
use App\Exports\BusinessSelectedExport;

class ExportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function user_export()
    {
        // ob_end_clean();
        // ob_start();
        return Excel::download(new UsersExport, 'users.xls');
    }

    public function itinerary_export()
    {
        // ob_end_clean();
        // ob_start();
        return Excel::download(new ItineraryExport, 'itinerary.xls');
    }

    public function business_export()
    {
        // ob_end_clean();
        // ob_start();
        return Excel::download(new BusinessExport, 'business.xls');
    }



    public function itinerary_business_export()
    {
        // ob_end_clean();
        // ob_start();
        return Excel::download(new ItineraryBusinessExport, 'itinerary-business.xls');
    }


    public function export_selected(Request $request)
    {
        // ob_end_clean();
        // ob_start();

        $items = $request->items;
        return (new PullExport($items))->download('itinerary-business-checklist.xls');
    }


    public function export_selected_with_business(Request $request)
    {
        // ob_end_clean();
        // ob_start();

        $items = $request->items;
        return (new ItineraryBusinessSelectedExport($items))->download('itinerary-business-list.xls');
    }

    public function export_selected_business(Request $request)
    {
        // ob_end_clean();
        // ob_start();

        $items = $request->items;
        return (new BusinessSelectedExport($items))->download('business-list.xls');
    }
}
