<?php

namespace App\Http\Controllers;

use App\Models\Models\Business;
use Illuminate\Http\Request;
use App\Models\Locations\Barangay;
use App\Models\Locations\Municipality;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('Business/Create', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }

    public function rewrite()
    {
        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('Business/Edit', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }


    public function view()
    {
        return Inertia::render('Business', [
            'hosting' => config('custom.url')
        ]);
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
     * @param  \App\Models\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        //
    }
}
