<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ISFRequest;
use App\Http\Resources\ISFResource;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ISF;

class ISFController extends Controller
{

    public function index()
    {
        return ISFResource::collection(ISF::orderby('id', 'DESC')->get());
    }

    public function store(ISFRequest $request)
    {

    }


    public function show(Request $request, $id)
    {
        $data = ISF::first();
        return response()->json(['data' => $data]);
    }


    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [

        ])->validate();

        return response()->json([
            'success' => true,
            'data' => $request->all()
        ]);
    }


    public function destroy(Request $request, $id)
    {
     
    }


    public function fetch(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = ISF::query();
        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
            }
        }

        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['household_head', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if (isset($params['filterField'])) {
            if ($params['filterField'] != "") {
                $count = ISF::where($params['filterField'], $params['filterValue'])->count();
            } else {
                $count = ISF::count();
            }
        } else {
            $count = ISF::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }

    public function getSearchfield(Request $request)
    {
        $data =  ISF::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
