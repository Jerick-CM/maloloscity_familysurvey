<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnvironmentAndDisasterRisk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "environment_and_disaster_risks";
    public $timestamps = true;
    protected $fillable = [
        'information_id',

        'victim_of_housefire',
        'victim_of_typoon_drought',
        'victim_of_earthquake_volcanic_eruption',
        'death_of_family_via_calamity',
        'house_demolition',
        'resided_in_relocation_resettlement',
    ];

    public function information()
    {
        return $this->belongsTo(RespondentsInformation::class);
    }
}
