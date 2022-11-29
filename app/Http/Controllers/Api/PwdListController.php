<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PWDRequest;
use App\Http\Resources\PWDResource;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\PWD;

class PwdListController extends Controller
{


    public function index()
    {
        return PWDResource::collection(PWD::orderby('id', 'DESC')->get());
    }

    public function store(PWDRequest $request)
    {
        Validator::make($request->all(), [
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'middle_name' => ['nullable'],
        ])->validate();

        DB::beginTransaction();
        try {

            $fullname = $request->first_name . " " . $request->middle_name . " " . $request->last_name . " " . $request->name_suffix;

            $PWD = PWD::create(
                [

                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'name_suffix' => $request->name_suffix,
                    'full_name' => $fullname,

                    'address' => $request->address,
                    'date_of_birth' => $request->date_of_birth,
                    'disability' => $request->disability,
                    'cause_of_disability' => $request->cause_of_disability,
            
                    'id_number' => $request->id_number,
                    'date_applied' => $request->date_applied,
                    'remarks' => $request->remarks,
                    'notes' => $request->notes,
                    'barangay' => $request->barangay,

                ]

            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json([
            'success' => true,
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = PWD::where('id', $id)->first();
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            // 'first_name' => ['required'],
        ])->validate();


        DB::beginTransaction();
        try {
            $isf = PWD::findOrfail($id);

            $isf->body_of_water_name = $request->body_of_water_name;
            $isf->body_of_water_type = $request->body_of_water_type;
            $isf->household_head = $request->household_head;
            $isf->birthdate = $request->birthdate;
            $isf->spouse_name = $request->spouse_name;

            $isf->spouse_birthdate = $request->spouse_birthdate;
            $isf->spouse_name = $request->spouse_name;
            $isf->tenurial_status = $request->tenurial_status;
            $isf->no_of_family_members = $request->no_of_family_members;
            $isf->street = $request->street;

            $isf->barangay = $request->barangay;
            $isf->latitude = $request->latitude;
            $isf->longitude = $request->longitude;
            $isf->balik_probinsya = $request->balik_probinsya;
            $isf->distance_to_waterway = $request->distance_to_waterway;

            $isf->zone = $request->zone;
            $isf->date = $request->date;
            $isf->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
        DB::commit();



        return response()->json([
            'success' => true,
            'data' => $request->all()
        ]);
    }


    public function destroy(Request $request, $id)
    {
        /* delete isf */
        $respondent = PWD::findOrfail($id);
        $respondent->delete();

        return response()->json([
            'success' => true,

        ]);
    }


    public function fetch(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = PWD::query();

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
                $count = PWD::where($params['filterField'], $params['filterValue'])->count();
            } else {
                $count = PWD::count();
            }
        } else {
            $count = PWD::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }

    public function getSearchfield(Request $request)
    {
        $data =  PWD::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
