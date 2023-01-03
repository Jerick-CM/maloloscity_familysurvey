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

use App\Exports\SoloParentExport;

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
                    'daughters' => $request->daughters,
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

        $reqs =  $reqs->leftJoin('soloparent_renewals', function ($join) {
            $join->on('soloparent_renewals.soloparent_id', '=', 'soloparents_list.id')
                ->on('soloparent_renewals.id', '=', DB::raw("(SELECT max(id) from soloparent_renewals WHERE soloparent_renewals.soloparent_id = soloparents_list.id AND soloparent_renewals.deleted_at is null)"));
        })->select(array('soloparents_list.*', 'soloparent_renewals.year as renew_year'));


        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
            }
        }

        if ($params['datefrom'] != "" && $params['dateto'] != "") {
            $reqs =  $reqs->whereBetween('soloparents_list.date_applied',  [$params['datefrom'], $params['dateto']]);
        } else if ($params['datefrom'] != "") {
            $reqs =  $reqs->whereDate('soloparents_list.date_applied', '>=', $params['datefrom']);
        } else if ($params['dateto'] != "") {
            $reqs =  $reqs->whereDate('soloparents_list.date_applied', '<=', $params['dateto']);
        }

        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['full_name', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        if ($request->options['sortBy']) {

            if ($request->options['sortBy'] == "latestyear.year") {

                $query =  $reqs->orderBy("date_of_issuance", strtoupper($request->options['sortType']))->offset(($options['page'] - 1) * $limit);
            } else if ($request->options['sortBy'] == "computed_renewal_year") {

                $query =  $reqs->orderBy("date_of_issuance", strtoupper($request->options['sortType']))->offset(($options['page'] - 1) * $limit);
            } else {
                $query =  $reqs->orderBy($request->options['sortBy'], strtoupper($request->options['sortType']))->offset(($options['page'] - 1) * $limit);
            }
        } else {

            $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        }

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

    public function destroy(Request $request, $id)
    {
        /* delete soloparent */
        $respondent = SoloParent::findOrfail($id);
        $respondent->delete();
        return response()->json([
            'success' => true,
        ]);
    }

    public function show(Request $request, $id)
    {
        $data = SoloParent::where('id', $id)->first();
        $soloparent  = SoloParent::find($id)->renewal;
        $date = SoloParent_renewal::where('soloparent_id', $id)->select('date_of_application')->orderBy('id', 'DESC')->first();
        return response()->json(
            [
                'data' => $data,
                'soloparent' => $soloparent,
                'date' => $date,
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

            $solo_parents = SoloParent::findOrfail($id);

            $solo_parents->first_name  = $request->first_name;
            $solo_parents->middle_name = $request->middle_name;
            $solo_parents->last_name = $request->last_name;
            $solo_parents->name_suffix = $request->name_suffix;

            if ($request->first_name != null &&  $request->last_name != null) {
                $solo_parents->full_name = $request->first_name . " " . $request->middle_name . " " . $request->last_name . " " . $request->name_suffix;
            }

            $solo_parents->address = $request->address;
            $solo_parents->date_of_birth = $request->date_of_birth;

            $solo_parents->id_number = $request->id_number;
            $solo_parents->remarks = $request->remarks;
            $solo_parents->notes = $request->notes;
            $solo_parents->barangay = $request->barangay;

            $solo_parents->sons = $request->sons;
            $solo_parents->daughters = $request->daughters;

            $solo_parents->save();

            $all_years = SoloParent_renewal::where('soloparent_id', $id)->select('year')->get();

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
                    SoloParent_renewal::where('soloparent_id', $id)->where('year', $val)->delete();
                }

                // add
                foreach ($diff_array_1 as $key => $val) {

                    SoloParent_renewal::create([
                        'year' => $val,
                        'soloparent_id' =>   $id,
                        'date_of_application' => $request->date_of_application,
                    ]);
                }
            } else {

                foreach ($request->year_renewal as $key => $val) {

                    SoloParent_renewal::create([
                        'year' => $val,
                        'soloparent_id' =>   $id,
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
            'all_year' =>  $all_years,
            'year_renewals' => $request->year_renewal

        ]);
    }

    public function export(Request $request)
    {

        $params = $request->params;

        $reqs = SoloParent::query();

        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
            }
        }

        if ($params['datefrom'] != "" && $params['dateto'] != "") {
            $reqs =  $reqs->whereBetween("soloparents_list.date_applied",  [$params['datefrom'], $params['dateto']]);
        } else if ($params['datefrom'] != "") {
            $reqs =  $reqs->whereDate('"soloparents_list.date_applied"', '>=', $params['datefrom']);
        } else if ($params['dateto'] != "") {
            $reqs =  $reqs->whereDate('"soloparents_list.date_applied"', '<=', $params['dateto']);
        }

        $reqs  = $reqs->select('soloparents_list.*');

        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['full_name', 'LIKE', "%" . $word . "%"]]);
        });

        $items = $reqs->get();

        return (new SoloParentExport($items))->download('SoloParents_data.xls');
    }
}
