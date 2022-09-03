<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respondents_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('user_id');
            $table->tinyInteger('full_name');
            $table->tinyInteger('first_name');
            $table->tinyInteger('middle_name');
            $table->tinyInteger('last_name');
            $table->tinyInteger('name_suffix');
            $table->tinyInteger('family_position');
            $table->tinyInteger('number_of_children');
            $table->tinyInteger('number_of_people_in_household');
            $table->tinyInteger('four_ps_beneficiary');
            $table->tinyInteger('four_ps_beneficiary_id');
            $table->tinyInteger('four_ps_beneficiary_date');
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
        Schema::dropIfExists('respondents_information');
    }
}
