<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnvironmentAndDisasterRiskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('environment_and_disaster_risks', function (Blueprint $table) {

            $table->id();
            $table->tinyInteger('information_id')->index('information_id')->foreign('information_id')->onDelete('cascade');

            // 45
            $table->tinyInteger('victim_of_housefire');
            $table->tinyInteger('victim_of_typoon_drought');
            $table->tinyInteger('victim_of_earthquake_volcanic_eruption');
            $table->tinyInteger('death_of_family_via_calamity');
            $table->tinyInteger('house_demolition');
            $table->tinyInteger('resided_in_relocation_resettlement');
            // 51
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
        Schema::dropIfExists('environment_and_disaster_risks');
    }
}
