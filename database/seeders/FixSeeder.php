<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
// use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\EconomicRisk;
use App\Models\EnvironmentAndDisasterRisk;
use App\Models\IndividualLifeCycleRisk;
use App\Models\SocialAndGovernanceRisk;

class FixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $first = IndividualLifeCycleRisk::where('information_id', 127)->first();

        $duplicates = IndividualLifeCycleRisk::where('id', '>', $first->id)->get();
        $nextId = 128;

        foreach ($duplicates as $duplicate) {
            $duplicate->information_id = $nextId;
            $duplicate->save();
            $nextId++;
        }

        $first = EconomicRisk::where('information_id', 127)->first();
        $duplicates = EconomicRisk::where('id', '>', $first->id)->get();
        $nextId = 128;
        foreach ($duplicates as $duplicate) {
            $duplicate->information_id = $nextId;
            $duplicate->save();
            $nextId++;
        }


        $first = EnvironmentAndDisasterRisk::where('information_id', 127)->first();
        $duplicates = EnvironmentAndDisasterRisk::where('id', '>', $first->id)->get();
        $nextId = 128;
        foreach ($duplicates as $duplicate) {
            $duplicate->information_id = $nextId;
            $duplicate->save();
            $nextId++;
        }

        $first = SocialAndGovernanceRisk::where('information_id', 127)->first();
        $duplicates = SocialAndGovernanceRisk::where('id', '>', $first->id)->get();
        $nextId = 128;
        foreach ($duplicates as $duplicate) {
            $duplicate->information_id = $nextId;
            $duplicate->save();
            $nextId++;
        }
        
    }
}
