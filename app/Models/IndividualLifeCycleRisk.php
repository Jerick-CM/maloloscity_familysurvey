<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndividualLifeCycleRisk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "individual_lifecycle_risks";
    public $timestamps = true;
    protected $fillable = [
        'information_id',
        // list
        'pregnancy_and_birth',
        'no_prenatal_checkup',
        'abortion',
        'miscarriage',
        'death_ofa_child',
        'no_postnatal_checkup',
        'kid_with_disability',
        'no_vaccine_in_childhood',
        'familymember_disability_dueto_accident_sickness',
        'death_dueto_accident_disaster',


        'familymember_severe_or_longtime_sickness',
        'no_awarded_medicalservice_healthcenter_or_hospital',
        'not_able_to_buy_medicine',
        'familymember_death_dueto_sickness',
        'not_ableto_enrol_in_daycare_preschool',
        'not_ableto_enrol_in_elementary',
        'not_ableto_enrol_in_highschool',
        'not_ableto_enrol_in_college',
        'stop_in_school_or_dropout_in_elementary_or_highschool',
        'drug_addiction',


        'teen_pregnancy_below_18yearsold',
        'ranaway_from_home',
        'familymember_in_abroad',
        'working_in_municipality_or_city',
        'separated_with_husbandwife',
        'death_of_husbandwife',
        'insufficient_food_security',
        'insufficient_clean_drinkable_water',
        'home_made_of_lightsalvagable_material',
        'scarcity_of_cleanbathroom',
    ];

    public function information()
    {
        return $this->belongsTo(RespondentsInformation::class);
    }
}
