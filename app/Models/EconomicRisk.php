<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EconomicRisk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "economic_risks";
    public $timestamps = true;

    protected $fillable = [
        'information_id',

        'no_work',
        'work_notalign_to_profession',
        'experienced_workaccident',
        'unpaid_loans',
        'victim_of_scamming',
        'victim_of_illegalrecruitment',
        'change_of_cityaddress',
        'no_lifeinsurance',
        'no_healthinsurance',
        'working_without_sss_or_gsis',

        'bankrupt_in_business',
        'mortgage_of_posessions',
        'loaned_money_from_relatives',
        'family_elder_without_pension',
    ];


    public function information()
    {
        return $this->belongsTo(RespondentsInformation::class);
    }
}
