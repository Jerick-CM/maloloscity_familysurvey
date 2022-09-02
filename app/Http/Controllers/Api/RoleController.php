<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
// use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Logs;
use App\Events\UserLogsEvent;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = Role::query();
        $logged_user = User::findOrFail(Auth::guard('web')->user()->id);
        if ($logged_user->hasRole('SUPERADMIN')) {
            // $data = $data
            //     ->get();
            $data = $data
                ->where('name', '!=', 'SUPERADMIN')
                ->where('name', '!=', 'ADMIN')
                ->get();
        } else  if ($logged_user->hasRole('ADMIN')) {
            $data = $data
                ->where('name', '!=', 'SUPERADMIN')
                ->where('name', '!=', 'ADMIN')
                ->get();
        } else {
            $data = $data
                ->where('name', '!=', 'SUPERADMIN')
                ->where('name', '!=', 'ADMIN')
                ->get();
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function index_edit()
    {
        $data = Role::query();
        $data = $data->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function index_user_edit()
    {

        $data = Role::query();
        $logged_user = User::findOrFail(Auth::guard('web')->user()->id);
        if ($logged_user->hasRole('SUPERADMIN')) {
            // $data = $data
            //     ->get();
            $data = $data
                ->where('name', '!=', 'SUPERADMIN')
                ->where('name', '!=', 'ADMIN')
                ->get();
        } else  if ($logged_user->hasRole('ADMIN')) {
            $data = $data
                ->where('name', '!=', 'SUPERADMIN')
                ->where('name', '!=', 'ADMIN')
                ->get();
        } else {
            $data = $data
                ->where('name', '!=', 'SUPERADMIN')
                ->where('name', '!=', 'ADMIN')
                ->get();
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $Role = Role::create($request->validated());

        return new RoleResource($Role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $Role)
    {
        return new RoleResource($Role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $Role)
    {
        $Role->update($request->validated());

        return new RoleResource($Role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $Role)
    {
        $Role->delete();

        return response()->noContent();
    }


    public function update_all(Request $request)
    {
        // Role::truncate();
        // DB::statement("ALTER TABLE `roles` AUTO_INCREMENT = 1;");

        if ($request->roles) {
            foreach ($request->roles as $key_check => $value_check) {
                if (!Role::where('id', $key_check + 1)->first()) {
                    Role::create(['name' => $value_check]);
                } else {
                    $role = Role::findOrFail($key_check + 1);
                    $role->name = $value_check;
                    $role->update();
                }
            }
        }

        $user = User::findOrfail(Auth::guard('web')->user()->id);
        event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_CHECKLIST, [
            'email'  =>   $user->email,
        ]));

        return response()->json([
            'success' => 1,
            'request' => $request->checklist,
        ]);
    }
}
