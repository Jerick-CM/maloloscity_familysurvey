<?php

namespace App\Http\Controllers;

use App\Models\Models\Pwd_list;
use Illuminate\Http\Request;
use App\Models\Locations\Barangay;
use App\Models\Locations\Municipality;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class PwdListController extends Controller
{
    public function index()
    {
        return Inertia::render('PWD/Index', [
            'hosting' => config('custom.url')
        ]);
    }

    public function handleEdit()
    {
        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('PWD/Edit', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }

    public function handleView()
    {
        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('PWD/View', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }

    public function handleCreate()
    {
        $municipalities = Municipality::select('mun_id as id', 'mun_name as value')->where('prov_id', 14)->orderBy('mun_name', 'asc')->get();
        $barangays = Barangay::select('brgy_id as id', 'brgy_name as value', DB::raw('CAST(mun_id AS UNSIGNED) AS parent'))->where('prov_id', 14)->orderBy('brgy_name', 'asc')->get();

        return Inertia::render('PWD/Create', [
            'barangays' => $barangays,
            'municipalities' => $municipalities,
        ]);
    }
}
