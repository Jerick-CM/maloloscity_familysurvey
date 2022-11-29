<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class PWDandSoloParentPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Page Access */
        Permission::create(['name' => 'Access-Page-PWD']);
        /* User Permission */
        Permission::create(['name' => 'Action Delete PWD']);
        Permission::create(['name' => 'Action Create PWD']);
        Permission::create(['name' => 'Action Edit PWD']);
        Permission::create(['name' => 'Action Show-All PWD']);
        Permission::create(['name' => 'Action Download PWD']);

        /** Page Access */
        Permission::create(['name' => 'Access-Page-SoloParent']);
        /* User Permission */
        Permission::create(['name' => 'Action Delete SoloParent']);
        Permission::create(['name' => 'Action Create SoloParent']);
        Permission::create(['name' => 'Action Edit SoloParent']);
        Permission::create(['name' => 'Action Show-All SoloParent']);
        Permission::create(['name' => 'Action Download SoloParent']);
    }
}
