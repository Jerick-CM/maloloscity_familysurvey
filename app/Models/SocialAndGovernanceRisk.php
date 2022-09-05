<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAndGovernanceRisk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "social_and_governance_risks";
    public $timestamps = true;
    protected $fillable = [
        'information_id',
        
        'victim_of_burglary',
        'experienced_buglary_holdup',
        'experienced_sexual_assult',
        'qna_knowledge_with_youth_gangs',
        'death_of_family_due_to_crime',
        'witnessed_actual_crime_in_comminity',
        'election_violence',
        'fear_of_disturbance_dueto_election',
        'rebellion_against_government',
        'rebellion_of_military',

        'violence_at_home',
        'violence_to_minor',
        'child_labor',
        'corruption_in_government',
        'nonparticipant_in_comunity_activities',
        'nonvoter',
        'non_membership_inany_organization',
        'claim_land_of_others',
        'harmful_tradition_to_people',
        'religious_misunderstanding_dispute',

        'experienced_slow_justicesystem',
        'discrimination_in_ethnicity_gender',
        'tribal_dispute',
        'other_harms_to_family',
    ];

    public function information()
    {
        return $this->belongsTo(RespondentsInformation::class);
    }
}
