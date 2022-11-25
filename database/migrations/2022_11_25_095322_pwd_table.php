<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PwdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pwd_list', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name_suffix')->nullable();
            $table->date('full_name')->nullable();

            $table->string('barangay')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
  
            $table->string('disability')->nullable();
            $table->string('cause_of_disability')->nullable();
            $table->string('id_number')->nullable();
            $table->string('date_of_application')->nullable();
            $table->string('remarks')->nullable();
            $table->string('note')->nullable();

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
          Schema::dropIfExists('pwd_list');
    }
}
