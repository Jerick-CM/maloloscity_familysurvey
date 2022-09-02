<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function get_role_id_creator($id)
    {

        $user = User::find($id);
        $roles = $user->roles;
        $role_id = null;
        foreach ($roles  as $k => $v) {
            if ($v['id'] >= 4) {
                $role_id = $v['id'];
            }
        }
        return $role_id;
    }

    public function get_role_id()
    {
        $auth_user = Auth::guard('web')->user();
        $user = User::find($auth_user->id);
        $roles = $user->roles;
        $role_id = null;
        foreach ($roles  as $k => $v) {
            if ($v['id'] >= 4) {
                $role_id = $v['id'];
            }
        }
        return $role_id;
    }



    public function check_fieldpersonnel()
    {
        $auth_user = Auth::guard('web')->user();

        $user = User::find($auth_user->id);
        $roles = $user->roles;
        $fieldpersonnel = false;
        foreach ($roles  as $k => $v) {
            if ($v['id'] == Role::USER) {
                $fieldpersonnel = true;
            }
        }
        return  $fieldpersonnel;
    }

    public function get_user()
    {
        $auth_user = Auth::guard('web')->user();
        $user = User::find($auth_user->id);
        return $user;
    }
}
