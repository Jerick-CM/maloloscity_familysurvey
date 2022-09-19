<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndividualLifeCycleRiskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_lifecycle_risks', function (Blueprint $table) {

            $table->id();
            $table->tinyInteger('information_id')->index('information_id')->foreign('information_id')->onDelete('cascade');

            $table->tinyInteger('pregnancy_and_birth');
            $table->tinyInteger('no_prenatal_checkup');
            $table->tinyInteger('abortion');
            $table->tinyInteger('miscarriage');
            $table->tinyInteger('death_ofa_child');
            $table->tinyInteger('no_postnatal_checkup');
            $table->tinyInteger('kid_with_disability');
            $table->tinyInteger('no_vaccine_in_childhood');
            $table->tinyInteger('familymember_disability_dueto_accident_sickness');
            $table->tinyInteger('death_dueto_accident_disaster');
            //10
            $table->tinyInteger('familymember_severe_or_longtime_sickness');
            $table->tinyInteger('no_awarded_medicalservice_healthcenter_or_hospital');
            $table->tinyInteger('not_able_to_buy_medicine');
            $table->tinyInteger('familymember_death_dueto_sickness');
            $table->tinyInteger('not_ableto_enrol_in_daycare_preschool');
            
            $table->tinyInteger('not_ableto_enrol_in_elementary');
            $table->tinyInteger('not_ableto_enrol_in_highschool');
            $table->tinyInteger('not_ableto_enrol_in_college');
     
            $table->tinyInteger('stop_in_school_or_dropout_in_elementary_or_highschool');
            //20
            $table->tinyInteger('drug_addiction');
            $table->tinyInteger('teen_pregnancy_below_18yearsold');
            $table->tinyInteger('ranaway_from_home');
            $table->tinyInteger('familymember_in_abroad');
            $table->tinyInteger('working_in_municipality_or_city');
            $table->tinyInteger('separated_with_husbandwife');
            $table->tinyInteger('death_of_husbandwife');
            $table->tinyInteger('insufficient_food_security');
            $table->tinyInteger('insufficient_clean_drinkable_water');
            $table->tinyInteger('home_made_of_lightsalvagable_material');
            $table->tinyInteger('scarcity_of_cleanbathroom');
            //30
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
        Schema::dropIfExists('individual_lifecycle_risks');
    }
}
