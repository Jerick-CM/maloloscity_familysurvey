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
            $table->string('full_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();;
            $table->string('last_name');
            $table->string('name_suffix')->nullable();;
            $table->string('family_position')->nullable();;
            $table->string('number_of_children')->nullable();;
            $table->string('number_of_people_in_household')->nullable();;
            $table->tinyInteger('four_ps_beneficiary')->nullable();;
            $table->string('four_ps_beneficiary_id')->nullable();;
            $table->string('four_ps_beneficiary_date')->nullable();;
            $table->string('region')->nullable();;
            $table->string('province')->nullable();;
            $table->string('city')->nullable();;
            $table->string('barangay')->nullable();;
            $table->string('sitio')->nullable();;
            $table->string('purok')->nullable();;
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
