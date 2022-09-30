<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ISF extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "respondents_information";
    public $timestamps = true;
    protected $appends = ['createddate', 'fourps'];
    protected $fillable = [
        'user_id',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'family_position',
        'number_of_children',
        'number_of_people_in_household',
        'four_ps_beneficiary',
        'four_ps_beneficiary_id',
        'four_ps_beneficiary_date',
        'region',
        'province',
        'city',
        'barangay',
        'sitio',
        'purok',
    ];
}
