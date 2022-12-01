<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PWD extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "pwd_list";
    public $timestamps = true;

    protected $fillable = [
        'id',

        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'full_name',

        'address',
        'date_of_birth',
        'gender',
        'disability',
        'cause_of_disability',

        'id_number',
        'date_applied',
        'remarks',
        'notes',
        'barangay',
    
    ];


    public function renewal()
    {
        return $this->hasMany(Pwd_renewal::class,'pwd_id');
    }
}
