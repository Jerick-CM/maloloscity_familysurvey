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
    protected $fillable = [
        'id',
    ];

    public function renewal()
    {
        return $this->hasMany(Pwd_renewal::class);
    }
}
