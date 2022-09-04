<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RespondentsInformation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "respondents_information";
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'family_position',
        'number_of_children',
        'number_of_people_in_household',
        'four_ps_beneficiary',
        'four_ps_beneficiary_id',
        'four_ps_beneficiary_date',
        'region',
        'province',
        'city',
        'barangay',
        'sitio',
        'purok',
    ];

    public function individual_life_risk()
    {
        return $this->hasOne(IndividualLifeCycleRisk::class, 'information_id', 'id');
    }

    public function economic_risk()
    {
        return $this->hasOne(EconomicRisk::class, 'information_id', 'id');
    }

    public function environmental_and_disaster_risk()
    {
        return $this->hasOne(EnvironmentAndDisasterRisk::class, 'information_id', 'id');
    }
    public function social_and_governance_risk()
    {
        return $this->hasOne(SocialAndGovernanceRisk::class, 'information_id', 'id');
    }
}