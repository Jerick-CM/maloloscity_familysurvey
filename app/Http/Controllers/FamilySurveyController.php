<?php

namespace App\Http\Controllers;

use App\Models\Locations\Barangay;
use App\Models\Locations\Municipality;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\RespondentsInformation;
use Illuminate\Http\Request;

class FamilySurveyController extends Controller
{
    public function index()
    {

        return Inertia::render('Forms/Index', [
            'hosting' => config('custom.url')
        ]);
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

    public function fetch1(Request $request)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = RespondentsInformation::query();
        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
            }
        }

        $reqs = $reqs->where(function ($query) use ($params) {

            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['full_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['first_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['last_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['middle_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['barangay', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $count = RespondentsInformation::where($params['filterField'], $params['filterValue'])->count();
            } else {
                $count = RespondentsInformation::count();
            }
        } else {
            $count = RespondentsInformation::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }

    public function fetch(Request $request)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = RespondentsInformation::query();
        
        // if (isset($params['filterField'])) {
        //     if ($params['filterField'] != "") {
        //         $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        //     }
        // }

        $reqs = $reqs->where(function ($query) use ($params) {

            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['full_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['first_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['last_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['middle_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['barangay', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        // if (isset($params['filterField'])) {
        //     if ($params['filterField'] != "") {
        //         $count = RespondentsInformation::where($params['filterField'], $params['filterValue'])->count();
        //     } else {
        //         $count = RespondentsInformation::count();
        //     }
        // } else {
        //     $count = RespondentsInformation::count();
        // }
        $count = RespondentsInformation::count();
        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }
}
