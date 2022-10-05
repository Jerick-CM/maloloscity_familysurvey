<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ISFPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Page Access */
        Permission::create(['name' => 'Access-Page-ISF']);
        /* User Permission */
        Permission::create(['name' => 'Action Delete ISF']);
        Permission::create(['name' => 'Action Create ISF']);
        Permission::create(['name' => 'Action Edit ISF']);
        Permission::create(['name' => 'Action Show-All ISF']);
        Permission::create(['name' => 'Action Download ISF']);

    }
}
