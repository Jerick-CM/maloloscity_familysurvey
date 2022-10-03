<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ISF extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "isf_and_illegal_encroachment";
    public $timestamps = true;
    protected $fillable = [
        'id',
        'body_of_water_name',
        'body_of_water_type',
        'household_head',
        'birthdate',
        'spouse_name',
        'spouse_birthdate',
        'tenurial_status',
        'no_of_family_members',
        'street',
        'barangay',
        'latitude',
        'longitude',
        'balik_probinsya',
        'distance_to_waterway',
        'zone',
        'date',
    ];
}
