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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('name'); // 1,2,3
            $table->tinyInteger('family_position');
            $table->tinyInteger('number_of_children');
            $table->tinyInteger('number_of_people_in_household');
            $table->tinyInteger('four_ps_beneficiary');
            $table->tinyInteger('four_ps_beneficiary_id');
            $table->tinyInteger('four_ps_beneficiary_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information_table');
    }
}
