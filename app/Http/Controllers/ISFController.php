<?php

namespace App\Http\Controllers;

use App\Models\Locations\Barangay;
use App\Models\Locations\Municipality;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ISFController extends Controller
{
    public function handleISFEdit($id)
    {

        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('ISF/Edit', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }
}
