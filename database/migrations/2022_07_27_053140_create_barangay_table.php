<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangay', function (Blueprint $table) {
            $table->id();
            $table->string('brgy_id');
            $table->string('prov_id');
            $table->string('mun_id');
            $table->string('brgy_name');
            $table->string('pmb');
            $table->string('brgyid');
            $table->string('brgy_tag');
            $table->string('brgy_base');
            $table->string('brgy_rdx');
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
        Schema::dropIfExists('barangay');
    }
};
