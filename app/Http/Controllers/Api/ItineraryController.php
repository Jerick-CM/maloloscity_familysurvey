<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItineraryRequest;
use App\Http\Resources\ItineraryResource;
use App\Models\Itinerary;
use App\Models\Checklist;
use App\Models\ItineraryBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Business;
use App\Models\ChecklistData;
use App\Models\Role;
use App\Models\Logs;
use App\Events\UserLogsEvent;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  ItineraryResource::collection(Itinerary::orderBy('id', 'DESC')->get());
    }

    public function fetch(Request $request, $id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;
        $reqs = Itinerary::query();

        $user = User::findOrFail($id);

        $role_id = $this->check_fieldpersonnel();


        $logged_user = User::findOrFail(Auth::guard('web')->user()->id);
        if ($logged_user->hasPermissionTo('Action Show-All Itinerary')) {
        } else {
            if ($role_id == Role::USER) {
                $reqs =  $reqs->where('assign_id', $id);
            } else {
                $user = $this->get_user();
                $reqs =  $reqs->where('user_id',     $user->id);
            }
        }


        $reqs = $reqs->where(function ($query) use ($params) {

            $word = $params['searchValue'];
            $word = str_replace(" ", "%",  $word);
            $query->where([['name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['businesses', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['control_number', 'LIKE', "%" . $word . "%"]])
                ->orWhere(
                    [['requestdate', 'LIKE', "%" . $word . "%"]]
                );
        })->take($options['rowsPerPage']);
        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();
        $count = Itinerary::count();

        return response()->json([
            'role_id' => $role_id,
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }


    public function index_user($id)
    {

        $user = User::findOrFail($id);
        if ($user->is_admin) {
            return  ItineraryResource::collection(Itinerary::orderBy('id', 'DESC')->get());
        } else {
            return  ItineraryResource::collection(Itinerary::where('assign_id', $id)->orderBy('id', 'DESC')->get());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItineraryRequest $request)
    {

        $unixTime = time();
        $timeZone = new \DateTimeZone('Asia/Taipei');
        $time = new \DateTime();
        $time->setTimestamp($unixTime)->setTimezone($timeZone);
        $formattedTime = $time->format('Ym');
        $control_number = $formattedTime . sprintf('%08d', $this->next());

        $role_id = $this->get_role_id();

        $Itinerary = Itinerary::create([
            'user_id' => $request->user_id,
            'control_number' => $control_number,
            'name' => $request->name,
            'businesses' => $request->businesses,
            'requestdate' => $request->requestdate,
            'notes' => $request->notes,
            'assign_id' => $request->assign_id,
            'role_id' => $role_id
        ]);

        $bss_ids = explode(",", $request->businesses);

        $role_id = $this->get_role_id();
        $checklist  = Checklist::where('role_id', $role_id)->get();

        foreach ($bss_ids as $value) {
            $itinerary_business = ItineraryBusiness::create([
                'itinerary_id' => $Itinerary->id,
                'business_id' => $value,
                'status' => 0,
            ]);
            $count = 0;
            foreach ($checklist as $key_check => $value_check) {
                $count =  $count + 1;
                ChecklistData::create([
                    'itinerary_businesss_id' => $itinerary_business->id,
                    'business_id' => $value,
                    'itinerary_id' => $Itinerary->id,
                    'index' => $count,
                    'label' => $value_check['name'],
                ]);
            }
        }

        $hashed = hash('ripemd128',  $Itinerary->id);
        $imageName = $hashed . '.png';

        if (!Storage::exists("app/public/qr")) {
            Storage::disk('public')->makeDirectory('qr');
        };

        \QrCode::size(500)
            ->format('png')
            ->generate(config('custom.url') . "/trace/"  . $hashed, storage_path("app/public/qr/" . $imageName));

        $Itinerary->qr_hash = $hashed;
        $Itinerary->qr_filename = $imageName;
        $Itinerary->qr_filepath = "storage/qr/" . $imageName;
        $Itinerary->save();


        $user = User::findOrfail($request->user_id);
        event(new UserLogsEvent($request->user_id, Logs::TYPE_CREATE_ITINERARY, [
            'name'  =>   $user->name,
            'control_number' => $control_number
        ]));

        return response()->json([
            'id' => $Itinerary->id,
            'success' => 1,
            'qr_path' => $Itinerary->qr_filepath,
            'qr_image' => $Itinerary->qr_filename,
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ Itinerary  $ Itinerary
     * @return \Illuminate\Http\Response
     */

    public function show(Itinerary $Itinerary)
    {
        return new  ItineraryResource($Itinerary);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ Itinerary  $ Itinerary
     * @return \Illuminate\Http\Response
     */
    public function update(ItineraryRequest $request,  Itinerary $Itinerary)
    {
        $Itinerary->update($request->validated());
        // return new  ItineraryResource($Itinerary);
        $user = User::findOrfail($request->user_id);
        event(new UserLogsEvent($request->user_id, Logs::TYPE_UPDATE_ITINERARY, [
            'name'  =>   $user->name,
            'itinerary_id' => $request->id,
        ]));
        return response()->json([
            'data' => $request->user('api')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ Itinerary  $ Itinerary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Itinerary $Itinerary)
    {
        $Itinerary->delete();
        ItineraryBusiness::where('itinerary_id', $Itinerary->id)->delete();
        return response()->noContent();
    }

    public function search(Request $request)
    {
        return  ItineraryResource::collection(Itinerary::where(function ($query) use ($request) {
            $word = str_replace(" ", "%", $request->searchValue);
            $query->where([['name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['businesses', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['control_number', 'LIKE', "%" . $word . "%"]])
                ->orWhere(
                    [['requestdate', 'LIKE', "%" . $request->searchValue . "%"]]
                );
        })->get());
    }

    public function select_search(Request $request)
    {
        return  ItineraryResource::collection(Itinerary::where(function ($query) use ($request) {
            $query->where([['name', 'LIKE', "%" . $request->searchValue . "%"]])
                ->orWhere([['businesses', 'LIKE', "%" . $request->searchValue . "%"]])
                ->orWhere(
                    [['requestdate', 'LIKE', "%" . $request->searchValue . "%"]]
                );
        })->limit(20)->get());
    }


    public function trace($hash)
    {

        $itinerary = Itinerary::where('qr_hash', $hash)->first();
        $businesses = Business::select('id as value', 'business_name as label', 'business_name', 'address', 'line_of_business', 'owner', 'capital', 'contact_number', 'business_identification_number as bin')
            ->whereIn('id', explode(",", $itinerary->businesses))->get();

        return Inertia::render('ItineraryTrace', [
            'item' => $itinerary,
            'businesses' => $businesses
        ]);
    }

    public function next()
    {
        $req = Itinerary::orderBy('id', 'DESC')->first();
        $nextId = $req ? $req->id + 1 : 1;
        return $nextId;
    }

    public function checklists()
    {
        $data = Checklist::select('id', 'name')->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function pull($id)
    {
        $data = Itinerary::select(
            'itineraries.id as it_id',
            'itinerary_businesses.status as i_stat',
            'businesses.*',
            'itinerary_businesses.id as iten_busi_id',
            'itinerary_businesses.remarks',
            'itinerary_businesses.start_at',
            'itinerary_businesses.end_at',
            'itinerary_businesses.completed_time',
            'itinerary_businesses.created_at as created_date',
            'users.name as username',
        )
            ->join('itinerary_businesses', 'itineraries.id', '=', 'itinerary_businesses.itinerary_id')
            ->join('businesses', 'businesses.id', '=', 'itinerary_businesses.business_id')
            ->join('users', 'users.id', '=', 'itineraries.assign_id')
            ->where('itineraries.id', '=', $id)
            ->whereNull('itinerary_businesses.deleted_at')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function completebusiness(Request $request)
    {

        $data =  ItineraryBusiness::select('*')
            ->where('itinerary_businesses.itinerary_id', '=', $request->it_id)
            ->where('itinerary_businesses.business_id', '=', $request->id)
            ->first();

        $data->status = 1;
        $data->end_at = now();

        $start = new Carbon($data->start_at);
        $end = new Carbon($data->end_at);
        $difference = $start->diffInSeconds($end);
        $data->completed_time =  gmdate('H:i:s',  $difference);

        $data->remarks = $request->remarks;
        $data->save();


        $countend = ItineraryBusiness::where('itinerary_id', '=', $request->it_id)
            ->whereNull('completed_time')
            ->count();

        if ($countend == 0) {

            $itinerary = Itinerary::findOrFail($request->it_id);

            $endtime = now();
            $start = new Carbon($itinerary->start_at);
            $end = new Carbon($itinerary->end_at);
            $difference = $start->diffInSeconds($end);

            $itinerary->completed_time =  gmdate('H:i:s',  $difference);
            $itinerary->end_at =  $endtime;
            $itinerary->save();
        }


        return response()->json([

            'end' => $countend,
            'itinerary_id' => $request->it_id,
            'business_id' => $request->id,
            'data' => $data,
        ]);
    }

    public function add_start(Request $request)
    {
        $data = Itinerary::findOrFail($request->id);
        $data->start_at = now();
        $data->update();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function add_finish(Request $request)
    {
        $data = Itinerary::findOrFail($request->id);
        $data->end_at = now();
        $data->update();

        $start = new Carbon($data->start_at);
        $end = new Carbon($data->end_at);

        $difference = $start->diffInSeconds($end);

        $data = Itinerary::findOrFail($request->id);
        $data->completed_time =  gmdate('H:i:s',  $difference);
        $data->update();

        return response()->json([
            'diff' => gmdate('H:i:s',  $difference),
            'data' => $data,
        ]);
    }

    public function add_business(Request $request)
    {

        if ($request->newbusiness == '') {
            return response()->json([
                'errors' => ['addbusiness' => ["Business Already included"]],
                'message' => "Business already included",
            ], 422);
        }

        $bss_ids = explode(",", $request->newbusiness);

        $data = Itinerary::findOrFail($request->itin_id)->first();
        $data->businesses =  $data->businesses . ',' . $request->newbusiness;
        $data->update();

        $checklist = Checklist::where('role_id', $data->role_id)->get();

        foreach ($bss_ids as $value) {
            $itinerary_business = ItineraryBusiness::create([
                'itinerary_id' => $request->itin_id,
                'business_id' => $value,
                'status' => 0,
            ]);

            $count = 0;
            foreach ($checklist as $key_check => $value_check) {
                $count =  $count + 1;

                ChecklistData::create([
                    'itinerary_businesss_id' => $itinerary_business->id,
                    'business_id' => $value,
                    'itinerary_id' => $request->itin_id,
                    'index' => $count,
                    'label' => $value_check['name'],
                ]);
            }
        }
    }

    public function itinerary_start($buss_id, $itin_id)
    {
        $countstart =  ItineraryBusiness::where('itinerary_businesses.itinerary_id', '=', $itin_id)
            ->whereNotNull('completed_time')
            ->count();

        if ($countstart == 0) {
            $itinerary = Itinerary::findOrFail($itin_id);
            $itinerary->start_at = now();
            $itinerary->save();
        }

        return response()->json([
            'data' =>  $countstart,

        ]);
    }

    public function checkvalues($buss_id, $itin_id)
    {

        //add start here
        $data =  ItineraryBusiness::select('*')
            ->where('itinerary_businesses.itinerary_id', '=', $itin_id)
            ->where('itinerary_businesses.business_id', '=', $buss_id)
            ->first();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function delete_pivot(Request $request)
    {
        $data = ItineraryBusiness::findOrFail($request->business_itinerary_id);

        ChecklistData::where('itinerary_businesss_id', $data->id)->where('business_id', $data->business_id)->delete();

        $itinerary = Itinerary::findOrFail($data->itinerary_id);

        $arr = explode(",", $itinerary->businesses);
        $arr  = array_map('intval',  $arr);
        $integerIDs = array_map('intval', $arr);

        $val = (int) $data->business_id;
        $key = array_search($val, $integerIDs, true);
        if ($key !== false) {
            array_splice($arr, $key, 1);
        }
        $str = implode(",", $arr);
        $itin_id = $data->itinerary_id;
        $itnry = Itinerary::findOrFail((int)$itin_id);
        $itnry->businesses = $str;
        $itnry->save();

        $itn_bus = ItineraryBusiness::findOrFail($request->business_itinerary_id);
        $itn_bus->delete();

        return response()->json([
            'pivot' =>   $itn_bus,
            'itinerary' => $itnry,
            'business_ids' => $str
        ]);
    }


    // public function get_business_history($itinerary_business_id, $business_id)
    // {

    //     $data = ItineraryBusiness::select('*')
    //         ->where('id', '!=', $itinerary_business_id)
    //         ->where('business_id', '=',  $business_id)
    //         ->where('status', '=', 1)->orderBy('id', 'DESC')
    //         ->get();

    //     return response()->json([
    //         'data' => $data,
    //     ]);
    // }

    public function business_start($itinerary_business_id, $business_id)
    {
        ItineraryBusiness::where('id', $itinerary_business_id)
            ->where('business_id', '=',  $business_id)
            ->whereNull('start_at')
            ->update(['start_at' => now()]);

        return response()->json([
            'itinerary_business_id' => $itinerary_business_id,
            'business_id' => $business_id,
            'start_business' => 1
        ]);
    }

    public function getchecklistdata($itinerary_business_id)
    {
        $data = ChecklistData::where('itinerary_businesss_id', $itinerary_business_id)->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function completebusiness_checklist(Request $request)
    {

        $data =  ItineraryBusiness::select('*')
            ->where('itinerary_businesses.itinerary_id', '=', $request->it_id)
            ->where('itinerary_businesses.business_id', '=', $request->id)
            ->first();

        $data->status = 1;
        $data->end_at = now();

        $start = new Carbon($data->start_at);
        $end = new Carbon($data->end_at);
        $difference = $start->diffInSeconds($end);
        $data->completed_time =  gmdate('H:i:s',  $difference);
        $data->remarks = $request->remarks;
        $data->save();

        $countend = ItineraryBusiness::where('itinerary_id', '=', $request->it_id)
            ->whereNull('completed_time')
            ->count();

        if ($countend == 0) {
            $itinerary = Itinerary::findOrFail($request->it_id);
            $endtime = now();
            $start = new Carbon($itinerary->start_at);
            $end = new Carbon($itinerary->end_at);
            $difference = $start->diffInSeconds($end);

            $itinerary->completed_time =  gmdate('H:i:s',  $difference);
            $itinerary->end_at =  $endtime;
            $itinerary->save();
        }

        $arr = [];
        if ($request->checklist) {
            foreach ($request->checklist  as $key => $value) {
                if ($key != 0) {
                    array_push($arr, $key);
                    $data = ChecklistData::where('itinerary_businesss_id', $request->itinerary_business_id)->where('business_id', $request->id)->where('index', $key)->update(['checkbox' => $value == null ? false : $value]);
                }
            }
        }

        return response()->json([
            'end' => $countend,
            'itinerary_id' => $request->it_id,
            'business_id' => $request->id,
            'itinerary_business_id' => $request->itinerary_business_id,
            'data' => $data,
            'loopcheck' => $arr,

        ]);
    }

    public function getbusinesshistory($itinerary_business_id, $business_id)
    {

        $data = ItineraryBusiness::select('id', 'remarks', 'itinerary_id')
            ->where('id', '!=', $itinerary_business_id)
            ->where('business_id', '=',  $business_id)
            ->where('status', '=', 1)->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function destroy_selected(Request $request)
    {

        $items = $request->items;
        $data = [];
        $ids = '';
        foreach ($items as $k => $v) {
            $data[] = [$v['id']];
            $ids  = $ids . " " . $v['id'];
            ItineraryBusiness::where('itinerary_id', $v['id'])->delete();
            Itinerary::where('id', $v['id'])->delete();
        }

        $user = User::findOrfail($request->user_id);
        event(new UserLogsEvent($user->id, Logs::TYPE_DELETE_ITINERARY_SELECTED, [
            'name'  =>   $user->name,
            'deleted_ids' =>  $ids
        ]));

        return response()->json([
            'data' => $data,
        ]);
    }

    public function destroy_with_log($id, $user_id)
    {

        Itinerary::where('id', $id)->delete();
        ItineraryBusiness::where('itinerary_id', $id)->delete();

        $user = User::findOrfail($user_id);
        event(new UserLogsEvent($user_id, Logs::TYPE_DELETE_BUSINESS, [
            'name'  =>   $user->name,
            'delete_id' => $id
        ]));

        return response()->noContent();
    }
}
