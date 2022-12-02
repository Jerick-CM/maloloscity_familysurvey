<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoloParentRequest;
use App\Http\Resources\SoloParentResource;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\SoloParent;
use App\Models\SoloParent_renewal;

class SoloParentListController extends Controller
{

    public function store(Request $request)
    {

        Validator::make($request->all(), [
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'middle_name' => ['nullable'],
        ])->validate();

        DB::beginTransaction();

        try {

            $fullname = $request->first_name . " " . $request->middle_name . " " . $request->last_name . " " . $request->name_suffix;

            $SoloParent = SoloParent::create(
                [
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'name_suffix' => $request->name_suffix,
                    'full_name' => $fullname,

                    'address' => $request->address,
                    'date_of_birth' => $request->date_of_birth,
                    'id_number' => $request->id_number,
                    'gender' => $request->gender,
                    'civil_status' => $request->civil_status,

                    'barangay' => $request->barangay,
                    'sons' => $request->sons,
                    'daugthers' => $request->daugthers,
                    'remarks' => $request->remarks,
                    'notes' => $request->notes,
                ]
            );

            SoloParent_renewal::create([
                'year' => $request->year,
                'soloparent_id' =>  $SoloParent->id,
                'date_of_application' => $request->date_of_application,
            ]);


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json([
            'success' => true,
        ]);
    }


    public function fetch(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = SoloParent::query();

        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
            }
        }

        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['full_name', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $count = SoloParent::where($params['filterField'], $params['filterValue'])->count();
            } else {
                $count = SoloParent::count();
            }
        } else {
            $count = SoloParent::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }

    public function getSearchfield(Request $request)
    {
        $data =  SoloParent::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
