<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role_User extends Model
{
    public $timestamps = true;
    use HasFactory, SoftDeletes;
    protected $table = "role_user";
    protected $fillable = [
        'user_id', 'role_id'
    ];
}
