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
use App\Models\Pwd_renewal;

use App\Exports\PwdExport;

class PwdListController extends Controller
{

    public function index()
    {
        // return PWDResource::collection(PWD::orderby('id', 'DESC')->get());
    }

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
                    'gender' => $request->gender,
                    'date_applied' => $request->date_of_application,

                ]

            );

            Pwd_renewal::create([
                'year' => $request->year,
                'pwd_id' =>  $PWD->id,
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

    public function show(Request $request, $id)
    {
        $data = PWD::where('id', $id)->first();
        $pwd_renewals  = PWD::find($id)->renewal;

        return response()->json(
            [
                'data' => $data,
                'pwd' => $pwd_renewals,
            ]
        );
    }

    public function update(Request $request, $id)
    {

        Validator::make($request->all(), [
            'date_of_application' => ['required']
        ])->validate();

        DB::beginTransaction();

        try {

            $pwd = PWD::findOrfail($id);

            $pwd->first_name  = $request->first_name;
            $pwd->middle_name = $request->middle_name;
            $pwd->last_name = $request->last_name;
            $pwd->name_suffix = $request->name_suffix;

            if ($request->first_name != null &&  $request->last_name != null) {
                $pwd->full_name = $request->first_name . " " . $request->middle_name . " " . $request->last_name . " " . $request->name_suffix;
            }

            $pwd->address = $request->address;
            $pwd->date_of_birth = $request->date_of_birth;
            $pwd->disability = $request->disability;
            $pwd->cause_of_disability = $request->cause_of_disability;

            $pwd->id_number = $request->id_number;
            $pwd->remarks = $request->remarks;
            $pwd->notes = $request->notes;
            $pwd->barangay = $request->barangay;

            $pwd->save();

            $all_years = Pwd_renewal::where('pwd_id', $id)->select('year')->get();

            if (count($all_years) > 0) {

                foreach ($all_years as $kk => $vv) {
                    $current_year[$kk] =  $vv['year'];
                }

                if (!empty($current_year)) {
                    $diff_array_0 = array_diff($current_year, $request->year_renewal);
                }

                if (!empty($current_year)) {
                    $diff_array_1 = array_diff($request->year_renewal, $current_year);
                }

                $diff_array_0 = array_values($diff_array_0);

                $diff_array_1 = array_values($diff_array_1);

                // remove 
                foreach ($diff_array_0 as $key => $val) {
                    Pwd_renewal::where('pwd_id', $id)->where('year', $val)->delete();
                }

                // add
                foreach ($diff_array_1 as $key => $val) {

                    Pwd_renewal::create([
                        'year' => $val,
                        'pwd_id' =>   $id,
                        'date_of_application' => $request->date_of_application,
                    ]);
                }
            } else {

                // no available year renewals
                foreach ($request->year_renewal as $key => $val) {

                    Pwd_renewal::create([
                        'year' => $val,
                        'pwd_id' =>   $id,
                        'date_of_application' => $request->date_of_application,
                    ]);
                }
            }
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json([

            'success' => true,
            'id' => $id,
            'all_year' =>  $all_years

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

        if ($params['datefrom'] != "" && $params['dateto'] != "") {
            $reqs =  $reqs->whereBetween("pwd_list.date_applied",  [$params['datefrom'], $params['dateto']]);
        } else if ($params['datefrom'] != "") {
            $reqs =  $reqs->whereDate('"pwd_list.date_applied"', '>=', $params['datefrom']);
        } else if ($params['dateto'] != "") {
            $reqs =  $reqs->whereDate('"pwd_list.date_applied"', '<=', $params['dateto']);
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


    public function export(Request $request)
    {

        $params = $request->params;

        $reqs = PWD::query();

        // if ($params['filterField'] != "") {

        //     if ($params['filterField'] == 'user_id') {
        //         $reqs =  $reqs->where("users.name", $params['filterValue']);
        //     }
        // }

        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
            }
        }

        if ($params['datefrom'] != "" && $params['dateto'] != "") {
            $reqs =  $reqs->whereBetween("pwd_list.created_at",  [$params['datefrom'], $params['dateto']]);
        } else if ($params['datefrom'] != "") {
            $reqs =  $reqs->whereDate('"pwd_list.created_at"', '>=', $params['datefrom']);
        } else if ($params['dateto'] != "") {
            $reqs =  $reqs->whereDate('"pwd_list.created_at"', '<=', $params['dateto']);
        }

        $reqs = $reqs->select('pwd_list.*');
        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['pwd_list.full_name', 'LIKE', "%" . $word . "%"]]);
        });

        $items = $reqs->get();

        return (new PwdExport($items))->download('pwd_data.xls');
    }
}
