<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoloParentListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soloparents_list', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name_suffix')->nullable();
            $table->string('full_name')->nullable();
            $table->string('address')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->string('id_number')->nullable();
  
            $table->string('sons')->nullable();
            $table->string('daughters')->nullable();
            
            $table->date('date_of_issuance')->nullable();
            $table->string('barangay')->nullable();

            $table->string('gender')->nullable();
            
            $table->string('civil_status')->nullable();
            $table->string('new_member')->nullable();
            $table->string('renewed_member')->nullable();

            $table->string('remarks')->nullable();
            $table->string('notes')->nullable();

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
        Schema::dropIfExists('soloparents_list');
    }
}
