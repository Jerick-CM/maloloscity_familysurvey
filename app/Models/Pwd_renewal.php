<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pwd_renewal extends Model
{
    use HasFactory, SoftDeletes;

    public function pwdaccount()
    {
        return $this->belongsTo(PWD::class);
    }
}
