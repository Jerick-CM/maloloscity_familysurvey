<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class TestController extends Controller
{
    public function test()
    {
        $user = User::findOrFail(Auth::guard('web')->user()->id);

        // var_dump($user);

        // $roles = $user->getRoleNames();

        // var_dump($roles);
        // $user->givePermissionTo('edit articles', 'delete articles');
        // echo "<br/>";



        // $user->givePermissionTo('Access-Page-User');
        // $user->givePermissionTo('Access-Page-Dashboard');
        // $user->givePermissionTo('Access-Page-Business');
        // $user->givePermissionTo('Access-Page-Logs');
        // $user->givePermissionTo('Access-Page-Itinerary');
        // // $permissions = $user->permissions;

        // // echo 'permission names';
        $permissionNames = $user->getPermissionNames(); // collection of name strings
        var_dump($permissionNames->toArray());


        // $allPermissions = Permission::pluck('name');
        // var_dump(  $allPermissions );
        // echo 'permission';
        // var_dump($permissions);

        // echo 'user without role';
        // $users_without_any_roles = User::doesntHave('roles')->get();
        // var_dump($users_without_any_roles);
    }
}
