<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Logs;
use App\Events\UserLogsEvent;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    const SUPERADMIN = 1;
    const ADMIN = 2;
    const USER = 3;
    const BPLO = 4;
    const BFP = 5;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(user::all());
    }


    public function index_user($id)
    {

        $user = User::findOrFail($id);
        if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
            return  UserResource::collection(User::get());
        } else if ($user->hasPermissionTo('Action Show-All User')) {
            // remove all superadmin and admin account
            $all = User::get();
            $admin = User::whereHas('roles', function ($query) {
                $query->where('name', 'ADMIN');
            })->get();
            $superadmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'SUPERADMIN');
            })->get();
            $all = $all->diff($admin);
            $minus = $all->diff($superadmin);

            return response()->json([
                'data' => $minus->toArray()
            ]);
        } else {
            return  UserResource::collection(User::where('id', $id)->get());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {

        if ($request->role == SELF::USER && $request->superadmin == 1) {
            return response()->json([
                'errors' => ['role' => ["User role not permitted to have superadmin role."]],
                'message' => "Password does't match",
            ], 422);
        }

        if ($request->role == SELF::USER && $request->admin == 1) {
            return response()->json([
                'errors' => ['role' => ["User role not permitted to have Admin role."]],
                'message' => "Password does't match",
            ], 422);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user_data = User::findOrfail($request->user_id);

        event(new UserLogsEvent($user_data->id, Logs::TYPE_CREATE_USER, [
            'email'  =>   $user_data->email,
            'create_email'  =>   $request->email,
        ]));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->superadmin == 1) {
            $user->assignRole('SUPERADMIN');
            /** Page Access */
            $user->givePermissionTo('Access-Page-User');
            $user->givePermissionTo('Access-Page-Dashboard');
            $user->givePermissionTo('Access-Page-Logs');
            $user->givePermissionTo('Access-Page-SurveyForm');

            /** Page Actions */
            $user->givePermissionTo('Action Edit Permission');

            /* User Permission */
            $user->givePermissionTo('Action Delete User');
            $user->givePermissionTo('Action Create User');
            $user->givePermissionTo('Action Edit User');
            $user->givePermissionTo('Action Show-All User');

            /* Survey Permission */
            $user->givePermissionTo('Action Delete SurveyForm');
            $user->givePermissionTo('Action Create SurveyForm');
            $user->givePermissionTo('Action Edit SurveyForm');

            /* Logs Permission */

            /* Admin and SuperAdmin Permission */
            $user->givePermissionTo('Action Settings Roles');
            $user->givePermissionTo('');
            $user->givePermissionTo('Action Download User');
            $user->givePermissionTo('Action Download Logs');
            $user->givePermissionTo('Action Download SurveyForm');
        }

        if ($request->admin == 1) {
            $user->assignRole('ADMIN');
            // $this->fn_give_admin_permission($user);
            /** Page Access */
            $user->givePermissionTo('Access-Page-User');
            $user->givePermissionTo('Access-Page-Dashboard');
            $user->givePermissionTo('Access-Page-Logs');
            $user->givePermissionTo('Access-Page-SurveyForm');
            /** Page Actions */
            $user->givePermissionTo('Action Edit Permission');
            /* User Permission */
            $user->givePermissionTo('Action Delete User');
            $user->givePermissionTo('Action Create User');
            $user->givePermissionTo('Action Edit User');
            $user->givePermissionTo('Action Show-All User');

            /* Logs Permission */

            /* Survey Permission */
            $user->givePermissionTo('Action Delete SurveyForm');
            $user->givePermissionTo('Action Create SurveyForm');
            $user->givePermissionTo('Action Edit SurveyForm');


            /* Admin and SuperAdmin Permission */
            $user->givePermissionTo('Action Settings Roles');
            $user->givePermissionTo('Action Download User');
            $user->givePermissionTo('Action Download Logs');
            $user->givePermissionTo('Action Download SurveyForm');
        }

        $role = Role::find($request->role);
        $user->assignRole($role->name);
        if ($role->id == 3) {
            /** Page Access */
            $user->givePermissionTo('Access-Page-User');
            $user->givePermissionTo('Access-Page-Dashboard');
            $user->givePermissionTo('Access-Page-SurveyForm');

            /* Survey Permission */
            $user->givePermissionTo('Action Create SurveyForm');
            $user->givePermissionTo('Action Edit SurveyForm');

            /* User Permission */
            $user->givePermissionTo('Action Edit User');
        }
        event(new Registered($user));
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function update(UserRequest $request, User $user)
    {

        if ($request->role == SELF::USER  && $request->superadmin == 1) {
            return response()->json([
                'errors' => ['role' => ["User role not permitted to have superadmin role."]],
                'message' => "Password does't match",
            ], 422);
        }

        if ($request->role == SELF::USER && $request->admin == 1) {
            return response()->json([
                'errors' => ['role' => ["User role not permitted to have Admin role."]],
                'message' => "Password does't match",
            ], 422);
        }

        $user->update($request->validated());
        $user->syncRoles([]);
        $user->syncPermissions([]);

        if (isset($request->superadmin)) {
            if ($request->superadmin == 1) {

                $user->assignRole('SUPERADMIN');
                /** Page Access */
                $user->givePermissionTo('Access-Page-User');
                $user->givePermissionTo('Access-Page-Dashboard');
                $user->givePermissionTo('Access-Page-Logs');
                $user->givePermissionTo('Access-Page-SurveyForm');

                /** Page Actions */
                $user->givePermissionTo('Action Edit Permission');

                /* User Permission */
                $user->givePermissionTo('Action Delete User');
                $user->givePermissionTo('Action Create User');
                $user->givePermissionTo('Action Edit User');
                $user->givePermissionTo('Action Show-All User');

                /* Survey Permission */
                $user->givePermissionTo('Action Delete SurveyForm');
                $user->givePermissionTo('Action Create SurveyForm');
                $user->givePermissionTo('Action Edit SurveyForm');

                /* Logs Permission */

                /* Admin and SuperAdmin Permission */
                $user->givePermissionTo('Action Settings Roles');
                $user->givePermissionTo('');
                $user->givePermissionTo('Action Download User');
                $user->givePermissionTo('Action Download Logs');
                $user->givePermissionTo('Action Download SurveyForm');
            }
        }

        if (isset($request->admin)) {
            if ($request->admin == 1) {
                $user->assignRole('ADMIN');
                // $this->fn_give_admin_permission($user);
                /** Page Access */
                $user->givePermissionTo('Access-Page-User');
                $user->givePermissionTo('Access-Page-Dashboard');
                $user->givePermissionTo('Access-Page-Logs');
                $user->givePermissionTo('Access-Page-SurveyForm');
                /** Page Actions */
                $user->givePermissionTo('Action Edit Permission');
                /* User Permission */
                $user->givePermissionTo('Action Delete User');
                $user->givePermissionTo('Action Create User');
                $user->givePermissionTo('Action Edit User');
                $user->givePermissionTo('Action Show-All User');

                /* Logs Permission */

                /* Survey Permission */
                $user->givePermissionTo('Action Delete SurveyForm');
                $user->givePermissionTo('Action Create SurveyForm');
                $user->givePermissionTo('Action Edit SurveyForm');


                /* Admin and SuperAdmin Permission */
                $user->givePermissionTo('Action Settings Roles');
                $user->givePermissionTo('Action Download User');
                $user->givePermissionTo('Action Download Logs');
                $user->givePermissionTo('Action Download SurveyForm');
            }
        }

        $role_new = Role::find($request->role);
        $user->assignRole($role_new->name);
        if ($role_new->id == 3) {
            /** Page Access */
            $user->givePermissionTo('Access-Page-User');
            $user->givePermissionTo('Access-Page-Dashboard');
            $user->givePermissionTo('Access-Page-SurveyForm');

            /* Survey Permission */
            $user->givePermissionTo('Action Create SurveyForm');
            $user->givePermissionTo('Action Edit SurveyForm');

            /* User Permission */
            $user->givePermissionTo('Action Edit User');
        }


        $user_change = User::findOrfail($request->id);
        $user_action = User::findOrfail($request->user_id);

        event(new UserLogsEvent($request->user_id, Logs::TYPE_UPDATE_USER, [
            'email'  =>   $user_action->email,
            'email_update'  =>   $user_change->email,
            'name'  =>  $user_change->name,
        ]));

        return response()->json([
            'newrole' => $request->role,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     **/

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }


    public function change_password(UserRequest $request, $id)
    {
        $request->validate([
            'password' => ['required', Rules\Password::defaults()],
            'new_password' => ['required', Rules\Password::defaults()],
            'retype_new_password' => ['required', Rules\Password::defaults()],
        ]);

        $user = DB::table('users')
            ->select('*')
            ->where('id', $id)
            ->first();

        $success = 0;
        if (Hash::check($request->password, $user->password)) {
            if ($request->new_password == $request->retype_new_password) {

                $user = User::findOrFail($id);
                $user->password = Hash::make($request->new_password);
                $user->save();
                $success = 1;

                $user_change = User::findOrfail($request->id);
                $user_action = User::findOrfail($request->user_id);

                event(new UserLogsEvent($request->user_id, Logs::TYPE_CHANGEPASSWORD_USER, [
                    'email'  =>   $user_action->email,
                    'email_change'  =>   $user_change->email,
                ]));
            } else {
                return response()->json([
                    'errors' => ['new_password' => ["Password does't match."]],
                    'message' => "Password does't match",
                ], 422);
            }
        } else {

            return response()->json([
                'errors' => ['password' => ["Password does't match."]],
                'message' => "Password does't match",
            ], 422);
        }

        return response()->json([
            'success' => $success,
            'user' => $user,
        ]);
    }


    public function reset_password(UserRequest $request, $id)
    {

        $request->validate([
            'reset_password' => ['required', Rules\Password::defaults()],
            'retype_reset_password' => ['required', Rules\Password::defaults()],
        ]);

        $success = 0;
        if ($request->reset_password == $request->retype_reset_password) {
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->reset_password);
            $user->save();
            $success = 1;

            $user_change = User::findOrfail($request->id);
            $user_action = User::findOrfail($request->user_id);

            event(new UserLogsEvent($request->user_id, Logs::TYPE_RESETPASSWORD_USER, [
                'email'  =>   $user_action->email,
                'email_change'  =>   $user_change->email,
            ]));
        } else {
            return response()->json([
                'errors' => ['new_password' => ["Password does't match."]],
                'message' => "Password does't match",
            ], 422);
        }

        return response()->json([
            'success' => $success,
            'user' => $user,
        ]);
    }

    public function exportdata()
    {
        ob_end_clean();
        ob_start();
        return Excel::download(new UsersExport, 'users.xls');
    }

    // delete user method
    public function  delete_user($id, $user_id)
    {

        $logged_user = User::findOrFail(Auth::guard('web')->user()->id);
        $user_for_delete = User::findOrfail($id);
        $success = 0;
        if ($logged_user->hasRole('SUPERADMIN')) {
            $success = 1;
        } else if ($logged_user->hasRole('ADMIN')) {
            if ($user_for_delete->hasRole('SUPERADMIN')) {
                return response()->json([
                    'errors' => ['Delete' => ["Account is SUPERADMIN"]],
                    'message' => "Cannot delete SUPERADMIN account",
                ], 422);
            } else {
                $success = 1;
            }
        } else {
            if ($user_for_delete->hasRole('SUPERADMIN')) {
                return response()->json([
                    'errors' => ['Delete' => ["Account is SUPERADMIN"]],
                    'message' => "Cannot delete SUPERADMIN account",
                ], 422);
            } else if ($user_for_delete->hasRole('ADMIN')) {
                return response()->json([
                    'errors' => ['Delete' => ["Account is ADMIN"]],
                    'message' => "Cannot delete ADMIN account",
                ], 422);
            } else {
                $success = 1;
            }
        }

        if ($success == 1) {
            event(new UserLogsEvent($logged_user->id, Logs::TYPE_DELETE_USER, [
                'email'  =>   $logged_user->email,
                'delete_email'  =>   $user_for_delete->email,
            ]));
            $user_for_delete->delete();
        }

        return response()->json([
            'success' => $success,
            'user' =>  $logged_user,
        ]);
    }

    public function  updatePermissions(Request $request)
    {

        $user = User::findOrFail($request->user_id);
        $user->syncPermissions($request->permissions);

        return response()->json([
            'success' => 1,
            'request' => $request->permissions,
            'user_id' => $request->user_id
        ]);
    }


    public function fetch(Request $request)
    {

        $id = Auth::guard('web')->user()->id;

        $user = User::findOrFail(Auth::guard('web')->user()->id);
        $options = $request->options;
        $params = $request->params;
        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = User::query();

        if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
        } else if ($user->hasPermissionTo('Action Show-All User')) {
        } else {
            $reqs =  $reqs->where('id', $id);
        }

        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['users.created_at', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.email', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        $query =  $reqs->orderBy('id', 'DESC')->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();
        $count = User::count();

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }
}
