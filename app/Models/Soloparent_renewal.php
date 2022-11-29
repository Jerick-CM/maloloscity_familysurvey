<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soloparent_renewal extends Model
{
    use HasFactory, SoftDeletes;
 
    public function soloparentaccount()
    {
        return $this->belongsTo(SoloParent::class);
    }
}
