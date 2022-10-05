<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsfAndIllegalEncroachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isf_and_illegal_encroachment', function (Blueprint $table) {
            $table->id();
            $table->string('body_of_water_name')->nullable();
            $table->string('body_of_water_type')->nullable();
            $table->string('household_head')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('spouse_name')->nullable();
            $table->date('spouse_birthdate')->nullable();
            $table->string('tenurial_status')->nullable();
            $table->string('no_of_family_members')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('balik_probinsya')->nullable();
            $table->string('distance_to_waterway')->nullable();
            $table->string('zone')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isf_and_illegal_encroachment');
    }
}
