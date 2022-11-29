<?php

namespace App\Http\Controllers;

use App\Models\Models\SoloParent_list;
use Illuminate\Http\Request;
use App\Models\Locations\Barangay;
use App\Models\Locations\Municipality;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SoloParentListController extends Controller
{
    public function index()
    {
        return Inertia::render('SoloParent/Index', [
            'hosting' => config('custom.url')
        ]);
    }


    public function handleISFEdit()
    {

        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('SoloParent/Edit', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }

    public function handleISFCreate()
    {

        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('SoloParent/Create', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }
}
