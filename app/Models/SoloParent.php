<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SoloParent extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "soloparents_list";
    public $timestamps = true;

    protected $appends = ['latestyear'];
    
    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'full_name',
        'address',
        'date_of_birth',
        'id_number',
        'sons',
        'daugthers',
        'date_of_issuance',
        'barangay',
        'gender',
        'civil_status',
        'new_member',
        'renewed_member',
        'remarks',
        'notes',
    ];

    public function renewal()
    {
        return $this->hasMany(Soloparent_renewal::class, 'soloparent_id');
    }

    public function getlatestyearAttribute()
    {
        $year = Soloparent_renewal::select('year')->where('soloparent_id', $this->id)->latest()->first();
        return $year;
    }

}
