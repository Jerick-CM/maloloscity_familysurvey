<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
// use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Page Access */
        Permission::create(['name' => 'Access-Page-User']);
        Permission::create(['name' => 'Access-Page-Dashboard']);
        Permission::create(['name' => 'Access-Page-Business']);
        Permission::create(['name' => 'Access-Page-Logs']);
        Permission::create(['name' => 'Access-Page-Itinerary']);

        /** Page Actions */
        Permission::create(['name' => 'Action Edit Permission']);

        /* User Permission */
        Permission::create(['name' => 'Action Delete User']);
        Permission::create(['name' => 'Action Create User']);
        Permission::create(['name' => 'Action Edit User']);
        Permission::create(['name' => 'Action Show-All User']);
        /* Business Permission */

        Permission::create(['name' => 'Action Delete Business']);
        Permission::create(['name' => 'Action Create Business']);
        Permission::create(['name' => 'Action Edit Business']);
        
        /* Itinerary Permission */

        Permission::create(['name' => 'Action Delete Itinerary']);
        Permission::create(['name' => 'Action Show-All Itinerary']);
        Permission::create(['name' => 'Action Create Itinerary']);
        Permission::create(['name' => 'Action Edit-AssignTo Itinerary']);
        Permission::create(['name' => 'Action Edit Itinerary']);
        Permission::create(['name' => 'Action Pull Itinerary']);
        /* Logs Permission */

        Permission::create(['name' => 'Action Download User']);
        Permission::create(['name' => 'Action Download Business']);
        Permission::create(['name' => 'Action Download Itinerary']);
        Permission::create(['name' => 'Action Download Logs']);
        Permission::create(['name' => 'Action Download Itinerary-Pull']);
        Permission::create(['name' => 'Action Print Itinerary-Pull']);

        Permission::create(['name' => 'Action Settings Roles']);
        Permission::create(['name' => 'Action Settings Checklist']);


    }
}
