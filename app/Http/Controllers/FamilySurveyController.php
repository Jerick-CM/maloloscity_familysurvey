<?php

namespace App\Http\Controllers;

use App\Models\Locations\Barangay;
use App\Models\Locations\Municipality;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class FamilySurveyController extends Controller
{
    public function index()
    {
        return Inertia::render('Forms/Index');
    }

    public function handleCreate()
    {
        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('Forms/Create', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }

    public function handleEdit($id)
    {
        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('Forms/Edit', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }
}
