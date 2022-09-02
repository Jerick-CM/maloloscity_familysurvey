<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChecklistRequest;
use App\Http\Resources\ChecklistResource;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Logs;
use App\Events\UserLogsEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return ChecklistResource::collection(Checklist::get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChecklistRequest $request)
    {
        $Checklist = Checklist::create($request->validated());

        return new ChecklistResource($Checklist);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checklist  $Checklist
     * @return \Illuminate\Http\Response
     */
    public function show(Checklist $Checklist)
    {
        return new ChecklistResource($Checklist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checklist  $Checklist
     * @return \Illuminate\Http\Response
     */
    public function update(ChecklistRequest $request, Checklist $Checklist)
    {
        $Checklist->update($request->validated());

        return new ChecklistResource($Checklist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checklist  $Checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $Checklist)
    {
        $Checklist->delete();

        return response()->noContent();
    }


    public function updateall(Request $request)
    {
        Checklist::truncate();

        DB::statement("ALTER TABLE `checklists` AUTO_INCREMENT = 1;");

        if ($request->checklist) {
            foreach ($request->checklist as $key_check => $value_check) {
                Checklist::create(['name' => $value_check]);
            }
        }

        $user = User::findOrfail($request->user_id);

        event(new UserLogsEvent($request->user_id, Logs::TYPE_UPDATE_CHECKLIST, [
            'email'  =>   $user->email,
        ]));

        return response()->json([
            'success' => 1,
            'request' => $request->checklist,
        ]);
    }

    public function fetch()
    {
        $role_id =  $this->get_role_id();
        $data = Checklist::where('role_id', $role_id)->get();

        return response()->json([
            'success' => 1,
            'data' =>  $data,
            'user' => Auth::guard('web')->user(),
            'role_id' => $role_id
        ]);
    }

    public function update_checkist(Request $request)
    {

        $role_id =  $this->get_role_id();
        Checklist::where('role_id', $role_id)->delete();
        if ($request->checklist) {
            foreach ($request->checklist as $key_check => $value_check) {
                Checklist::create(['name' => $value_check, 'role_id' => $role_id]);
            }
        }

        $user = User::findOrfail($request->user_id);
        event(new UserLogsEvent($request->user_id, Logs::TYPE_UPDATE_CHECKLIST, [
            'email'  =>   $user->email,
        ]));

        return response()->json([
            'success' => 1,
            'request' => $request->checklist,
            'role_id' => $role_id
        ]);
    }
}
