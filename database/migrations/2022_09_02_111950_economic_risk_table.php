<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EconomicRiskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('economic_risks', function (Blueprint $table) {

            $table->id();
            $table->tinyInteger('information_id')->index('information_id')->foreign('information_id')->onDelete('cascade');

            // 30
            $table->tinyInteger('no_work');
            $table->tinyInteger('work_notalign_to_profession');
            $table->tinyInteger('experienced_workaccident');
            $table->tinyInteger('unpaid_loans');
            $table->tinyInteger('victim_of_scamming');
            $table->tinyInteger('victim_of_illegalrecruitment');
            $table->tinyInteger('change_of_cityaddress');
            $table->tinyInteger('no_lifeinsurance');
            $table->tinyInteger('no_healthinsurance');
            $table->tinyInteger('working_without_sss_or_gsis');
            // 40
            $table->tinyInteger('bankrupt_in_business');
            $table->tinyInteger('mortgage_of_posessions');
            $table->tinyInteger('loaned_money_from_relatives');
            $table->tinyInteger('family_elder_without_pension');
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
        Schema::dropIfExists('economic_risks');
    }
}
