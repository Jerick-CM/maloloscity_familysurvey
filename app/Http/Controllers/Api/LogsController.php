<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogsRequest;
use App\Http\Resources\LogsResource;
use App\Models\Logs;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // limit(15000)->
    public function index()
    {
        return LogsResource::collection(Logs::orderby('id', 'DESC')->get());
        // return LogsResource::collection(Logs::all());
    }

    public function fetch(Request $request)
    {
        $options = $request->options;
        $params = $request->params;
        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = Logs::query();
        $reqs  = $reqs->select('logs.*')->join('users', 'users.id', '=', 'logs.user_id');
        $reqs = $reqs->where(function ($query) use ($params) {

            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['meta', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.email', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();
        $count = Logs::count();

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LogsRequest $request)
    {
        $business = Logs::create($request->validated());
        return new LogsResource($business);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logs  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Logs $business)
    {
        return new LogsResource($business);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logs  $business
     * @return \Illuminate\Http\Response
     */
    public function update(LogsRequest $request, Logs $business)
    {
        $business->update($request->validated());

        return new LogsResource($business);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logs  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logs $business)
    {
        $business->delete();

        return response()->noContent();
    }

    public function search(LogsRequest $request)
    {
        $data = Logs::join('users', 'users.id', '=', 'logs.user_id')->where(function ($query) use ($request) {
            $word = str_replace(" ", " %", $request->searchValue);
            $query->where([['meta', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.email', 'LIKE', "%" . $word . "%"]]);
        })->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
