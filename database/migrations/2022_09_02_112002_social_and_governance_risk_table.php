<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SocialAndGovernanceRiskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_and_governance_risks', function (Blueprint $table) {

            $table->id();
            $table->tinyInteger('information_id')->index('information_id')->foreign('information_id')->onDelete('cascade');

            // 51
            $table->tinyInteger('victim_of_burglary');
            $table->tinyInteger('experienced_buglary_holdup');
            $table->tinyInteger('experienced_sexual_assult');
            $table->tinyInteger('qna_knowledge_with_youth_gangs');
            $table->tinyInteger('death_of_family_due_to_crime');
            $table->tinyInteger('witnessed_actual_crime_in_comminity');
            $table->tinyInteger('election_violence');
            $table->tinyInteger('fear_of_disturbance_dueto_election');
            $table->tinyInteger('rebellion_against_government');
            $table->tinyInteger('rebellion_of_military');
            // 61
            $table->tinyInteger('violence_at_home');
            $table->tinyInteger('violence_to_minor');
            $table->tinyInteger('child_labor');
            $table->tinyInteger('corruption_in_government');
            $table->tinyInteger('nonparticipant_in_comunity_activities');
            $table->tinyInteger('nonvoter');
            $table->tinyInteger('non_membership_inany_organization');
            $table->tinyInteger('claim_land_of_others');
            $table->tinyInteger('harmful_tradition_to_people');
            $table->tinyInteger('religious_misunderstanding_dispute');
            // 71
            $table->tinyInteger('experienced_slow_justicesystem');
            $table->tinyInteger('discrimination_in_ethnicity_gender');
            $table->tinyInteger('tribal_dispute');
            $table->tinyInteger('other_harms_to_family');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_and_governance_risks');
    }
}
