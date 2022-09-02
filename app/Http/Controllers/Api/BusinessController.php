<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Events\UserLogsEvent;
use App\Models\User;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function fetch(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = Business::query();
        if (isset($params['filterField'])) {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs = $reqs->where(function ($query) use ($params) {

            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['business_identification_number', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['line_of_business', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['business_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['capital', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['address', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['strategic_location', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['barangay', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['owner', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();
        $count =    Business::count();

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }


    public function index()
    {
        return BusinessResource::collection(Business::limit(1000)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(BusinessRequest $request)
    {

        Validator::make($request->all(), [
            'sequence_number' => ['nullable'],
            'business_identification_number' => ['nullable'],
            'line_of_business' => ['required'],
            'business_name' => ['required'],
            'owner' => ['required'],
            'address' => ['required'],
            'address1' => ['nullable'],
            'barangay' => ['required'],
            'code' => ['nullable'],
            'stat' => ['nullable'],
            'gross_receipts' => ['nullable'],
            'capital' => ['nullable'],
            'permit_number' => ['nullable'],
            'business_tax' => ['nullable'],
            'fees' => ['nullable'],
            'surcharge' => ['nullable'],
            'total' => ['nullable'],
            'official_receipt_number' => ['nullable'],
            'official_receipt_date' => ['nullable'],
            'terms' => ['nullable'],
            'tax_year' => ['nullable'],
            'qtr_paid' => ['nullable'],
            'contact_number' => ['required'],
            'number_of_employees' => ['nullable'],
            'status' => ['nullable'],
            'first_name' => ['nullable'],
            'middle_name' => ['nullable'],
            'last_name' => ['nullable'],
            'strategic_location' => ['nullable'],
            'description' => ['nullable'],
        ])->validate();


        $business = Business::create(
            [

                'user_id' => Auth::guard('web')->user()->id,
                'business_identification_number' => $request->business_identification_number,
                'line_of_business' =>   $request->line_of_business,
                'business_name' =>  $request->business_name,
                'owner' => $request->owner,
                'address' => $request->address,
                'address1' => $request->address1,
                'barangay' => $request->barangay,
                'code' => $request->code,
                'stat' => $request->statF,
                'gross_receipts' => $request->gross_receipts,
                'capital' => $request->capital,
                'permit_number' => $request->permit_number,
                'business_tax' => $request->business_tax,
                'fees' => $request->fees,
                'surcharge' => $request->surcharge,
                'total' => $request->total,
                'official_receipt_number' => $request->official_receipt_number,
                'official_receipt_date' => $request->official_receipt_date,
                'terms' => $request->terms,
                'tax_year' => $request->tax_year,
                'qtr_paid' => $request->qtr_paid,
                'contact_number' => $request->contact_number,
                'number_of_employees' => $request->number_of_employees,
                'status' => $request->status,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'strategic_location' => $request->strategic_location,
                'description' => $request->description,
            ]



        );

        $user = User::findOrfail($request->user_id);
        event(new UserLogsEvent($request->user_id, Logs::TYPE_CREATE_BUSINESS, [
            'email'  =>   $user->email,
            'business_name'  =>   $request->business_name,
        ]));
        return new BusinessResource($business);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        return new BusinessResource($business);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(BusinessRequest $request, Business $business)
    {
        $business->update($request->validated());
        $user = User::findOrfail($request->user_id);
        event(new UserLogsEvent($request->user_id, Logs::TYPE_UPDATE_BUSINESS, [
            'email'  =>   $user->email,
            'business_name' => $request->business_name,
        ]));
        return new BusinessResource($business);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        $business->delete();

        return response()->noContent();
    }

    public function search(Request $request)
    {

        $data = Business::query();

        if (isset($request->filterField)) {

            $data =  $data->where($request->filterField, $request->filterValue);
        }

        switch ($request->searchField) {
            case  '':
                $data = $data->where(function ($query) use ($request) {
                    $word = $request->searchValue;
                    $query->where([['business_identification_number', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['line_of_business', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['business_name', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['capital', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['address', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['strategic_location', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['barangay', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['owner', 'LIKE', "%" . $word . "%"]]);
                });
                break;
            case  $request->searchField:
                $data = $data->where(function ($query) use ($request) {
                    $word = str_replace(" ", " %", $request->searchValue);
                    if ($request->searchField == "barangay") {
                        $query->where([[$request->searchField, 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['address', 'LIKE', "%" . $word . "%"]]);
                    } else {
                        $query->where([[$request->searchField, 'LIKE', "%" . $word . "%"]]);
                    }
                });
                break;
            default:
                $data = $data->where(function ($query) use ($request) {
                    $word = str_replace(" ", " %", $request->searchValue);

                    $query->where([['business_identification_number', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['line_of_business', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['business_name', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['capital', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['address', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['strategic_location', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['barangay', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['owner', 'LIKE', "%" . $word . "%"]]);
                });
        }

        $data = $data->get();

        return response()->json([
            'data' => $data,
        ]);
    }


    public function search_old(Request $request)
    {

        switch ($request->searchField) {
            case  '':
                return BusinessResource::collection(


                    Business::where(function ($query) use ($request) {
                        $word = str_replace(" ", " %", $request->searchValue);

                        $query->where([['business_identification_number', 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['line_of_business', 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['business_name', 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['capital', 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['address', 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['strategic_location', 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['barangay', 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['owner', 'LIKE', "%" . $word . "%"]]);
                    })->get()

                );

                break;
            case  $request->searchField:
                return BusinessResource::collection(Business::where(function ($query) use ($request) {
                    $word = str_replace(" ", " %", $request->searchValue);
                    if ($request->searchField == "barangay") {
                        $query->where([[$request->searchField, 'LIKE', "%" . $word . "%"]])
                            ->orWhere([['address', 'LIKE', "%" . $word . "%"]]);
                    } else {
                        $query->where([[$request->searchField, 'LIKE', "%" . $word . "%"]]);
                    }
                })->get());
                break;
            default:
                return BusinessResource::collection(Business::where(function ($query) use ($request) {

                    $word = str_replace(" ", " %", $request->searchValue);
                    $query->where([['business_identification_number', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['line_of_business', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['business_name', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['capital', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['address', 'LIKE', "%" . $word . "%"]])
                        ->orWhere([['strategic_location', 'LIKE', "%" . $word . "%"]])
                        ->orWhere(
                            [['barangay', 'LIKE', "%" . $word . "%"]]
                        )
                        ->orWhere([['owner', 'LIKE', "%" . $word . "%"]]);
                })->get());
        }
    }

    public function select_search(Request $request)
    {

        return BusinessResource::collection(Business::where(function ($query) use ($request) {
            $query->where([['business_identification_number', 'LIKE', "%" . $request->searchValue . "%"]])
                ->orWhere([['line_of_business', 'LIKE', "%" . $request->searchValue . "%"]])
                ->orWhere([['business_name', 'LIKE', "%" . $request->searchValue . "%"]])
                ->orWhere([['owner', 'LIKE', "%" . $request->searchValue . "%"]])
                ->orWhere([['capital', 'LIKE', "%" . $request->searchValue . "%"]])
                ->orWhere(
                    [['barangay', 'LIKE', "%" . $request->searchValue . "%"]]
                );
        })->limit(1000)->get());
    }

    public function get_selectfield(Request $request)
    {

        $data =  Business::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->limit(1000)->get();

        return response()->json([
            'data' => $data,

        ]);
    }
    public function get_by_ids(Request $request)
    {
        $business = Business::select('id', 'capital', 'business_name', 'address', 'line_of_business', 'owner')->whereIn('id', explode(",", $request->ids))->get();
        return response()->json([
            'data' =>  $business,
        ]);
    }

    public function strategic_location($location)
    {
        $data =  Business::select('id as value', 'business_name as label', 'business_name', 'address', 'line_of_business', 'owner', 'capital')->where('strategic_location', $location)->get();

        return response()->json([
            'data' => $data,

        ]);
    }

    public function index_itinerary()
    {

        $data =  Business::select('id as value', 'business_name as label', 'business_name', 'address', 'line_of_business', 'owner', 'capital')->limit(200)->get();
        return response()->json([
            'data' => $data,

        ]);
    }

    // public function test()
    // {
    //     return BusinessResource::collection(Business::limit(200)->get());
    // }

    public function destroy_with_log($id, $user_id)
    {
        $business =  Business::where('id', $id)->first();

        $user = User::findOrfail($user_id);

        event(new UserLogsEvent($user_id, Logs::TYPE_DELETE_BUSINESS, [
            'email'  =>   $user->email,
            'business_name' => $business->business_name
        ]));

        $business->delete();
        return response()->noContent();
    }
}
