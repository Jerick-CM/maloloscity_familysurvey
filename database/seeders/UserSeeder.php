<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
// use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Super Admin */
        $user = User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@maloloscity.gov.ph',
            'is_admin' => 1,
            'password' =>  Hash::make(config('custom.password_superadmin'))
        ]);
        $user->assignRole('ADMIN');
        $user->assignRole('BPLO');
        $user->assignRole('SUPERADMIN');

        /** Permissions ALl */
        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Business');
        $user->givePermissionTo('Access-Page-Logs');
        $user->givePermissionTo('Access-Page-Itinerary');

        /** Page Actions */
        $user->givePermissionTo('Action Edit Permission');

        /* User Permission */
        $user->givePermissionTo('Action Delete User');
        $user->givePermissionTo('Action Create User');
        $user->givePermissionTo('Action Edit User');
        $user->givePermissionTo('Action Show-All User');
        /* Business Permission */

        $user->givePermissionTo('Action Delete Business');
        $user->givePermissionTo('Action Create Business');
        $user->givePermissionTo('Action Edit Business');

        /* Itinerary Permission */
        $user->givePermissionTo('Action Delete Itinerary');
        $user->givePermissionTo('Action Show-All Itinerary');
        $user->givePermissionTo('Action Create Itinerary');
        $user->givePermissionTo('Action Edit Itinerary');
        $user->givePermissionTo('Action Pull Itinerary');
        $user->givePermissionTo('Action Edit-AssignTo Itinerary');
        /* Logs Permission */

        /* Admin and SuperAdmin Permission */
        $user->givePermissionTo('Action Settings Roles');
        $user->givePermissionTo('Action Settings Checklist');
        $user->givePermissionTo('Action Download User');
        $user->givePermissionTo('Action Download Business');
        $user->givePermissionTo('Action Download Itinerary');
        $user->givePermissionTo('Action Download Logs');
        $user->givePermissionTo('Action Download Itinerary-Pull');
        $user->givePermissionTo('Action Print Itinerary-Pull');

        /* Admin */
        $user = User::create([
            'name' => 'Admin Account',
            'email' => 'admin@maloloscity.gov.ph',
            'is_admin' => 1,
            'password' =>  Hash::make(config('custom.password_admin'))
        ]);

        $user->assignRole('ADMIN');
        $user->assignRole('BPLO');

        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Business');
        $user->givePermissionTo('Access-Page-Logs');
        $user->givePermissionTo('Access-Page-Itinerary');
        /** Page Actions */
        $user->givePermissionTo('Action Edit Permission');
        /* User Permission */
        $user->givePermissionTo('Action Delete User');
        $user->givePermissionTo('Action Create User');
        $user->givePermissionTo('Action Edit User');
        $user->givePermissionTo('Action Show-All User');
        /* Business Permission */
        $user->givePermissionTo('Action Create Business');
        $user->givePermissionTo('Action Edit Business');
        /* Itinerary Permission */
        $user->givePermissionTo('Action Delete Itinerary');
        $user->givePermissionTo('Action Show-All Itinerary');
        $user->givePermissionTo('Action Create Itinerary');
        $user->givePermissionTo('Action Edit Itinerary');
        $user->givePermissionTo('Action Pull Itinerary');
        $user->givePermissionTo('Action Edit-AssignTo Itinerary');
        /* Logs Permission */

        /* Admin and SuperAdmin Permission */
        $user->givePermissionTo('Action Settings Roles');
        $user->givePermissionTo('Action Settings Checklist');
        $user->givePermissionTo('Action Download User');
        $user->givePermissionTo('Action Download Business');
        $user->givePermissionTo('Action Download Itinerary');
        $user->givePermissionTo('Action Download Logs');
        $user->givePermissionTo('Action Download Itinerary-Pull');
        $user->givePermissionTo('Action Print Itinerary-Pull');

        /* BPLO */
        $user = User::create([
            'name' => 'BPLO Account',
            'email' => 'bplo@maloloscity.gov.ph',
            'is_admin' => 1,
            'password' =>  Hash::make(config('custom.password_admin'))
        ]);
        $user->assignRole('BPLO');

        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Business');
        $user->givePermissionTo('Access-Page-Logs');
        $user->givePermissionTo('Access-Page-Itinerary');
        /** Page Actions */

        /* User Permission */
        $user->givePermissionTo('Action Create User');
        $user->givePermissionTo('Action Delete User');
        $user->givePermissionTo('Action Edit User');
        $user->givePermissionTo('Action Show-All User');
        /* Business Permission */
        $user->givePermissionTo('Action Create Business');
        $user->givePermissionTo('Action Edit Business');
        /* Itinerary Permission */
        $user->givePermissionTo('Action Delete Itinerary');
        $user->givePermissionTo('Action Create Itinerary');
        $user->givePermissionTo('Action Edit Itinerary');
        $user->givePermissionTo('Action Edit-AssignTo Itinerary');
        /* Logs Permission */
        $user->givePermissionTo('Action Settings Checklist');
        /* BFP */
        $user = User::create([
            'name' => 'BFP Account',
            'email' => 'bfp@maloloscity.gov.ph',
            'is_admin' => 1,
            'password' =>  Hash::make(config('custom.password_admin'))
        ]);

        $user->assignRole('BFP');

        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Business');
        $user->givePermissionTo('Access-Page-Logs');
        $user->givePermissionTo('Access-Page-Itinerary');
        /** Page Actions */
        /* User Permission */
        $user->givePermissionTo('Action Create User');
        $user->givePermissionTo('Action Delete User');
        $user->givePermissionTo('Action Edit User');
        $user->givePermissionTo('Action Show-All User');
        /* Business Permission */
        $user->givePermissionTo('Action Create Business');
        $user->givePermissionTo('Action Edit Business');

        /* Itinerary Permission */
        $user->givePermissionTo('Action Delete Itinerary');
        $user->givePermissionTo('Action Create Itinerary');
        $user->givePermissionTo('Action Edit Itinerary');
        $user->givePermissionTo('Action Edit-AssignTo Itinerary');
        /* Logs Permission */
        $user->givePermissionTo('Action Settings Checklist');
        $user = User::create([
            'name' => 'User Account 1',
            'email' => 'user1@maloloscity.gov.ph',
            'is_admin' => 0,
            'password' =>  Hash::make(config('custom.password_user'))
        ]);

        $user->assignRole('USER');

        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Business');
        $user->givePermissionTo('Access-Page-Itinerary');
        /** Page Actions */
        /* User Permission */
        $user->givePermissionTo('Action Edit User');
        /* Business Permission */
        $user->givePermissionTo('Action Create Business');
        // $user->givePermissionTo('Action Edit Business');
        /* Itinerary Permission */
        $user->givePermissionTo('Action Edit Itinerary');
        $user->givePermissionTo('Action Pull Itinerary');

        $user = User::create([
            'name' => 'User Account 2',
            'email' => 'user2@maloloscity.gov.ph',
            'is_admin' => 0,
            'password' =>  Hash::make(config('custom.password_user'))
        ]);

        $user->assignRole('USER');
        /** Page Access */
        $user->givePermissionTo('Access-Page-User');
        $user->givePermissionTo('Access-Page-Dashboard');
        $user->givePermissionTo('Access-Page-Business');
        $user->givePermissionTo('Access-Page-Itinerary');
        /** Page Actions */
        /* User Permission */
        $user->givePermissionTo('Action Edit User');
        /* Business Permission */
        $user->givePermissionTo('Action Create Business');
        // $user->givePermissionTo('Action Edit Business');
        /* Itinerary Permission */
        $user->givePermissionTo('Action Edit Itinerary');
        $user->givePermissionTo('Action Pull Itinerary');
    }
}
