<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;


class Checklist extends Model
{
    use HasFactory;
    protected $table = "checklists";
    protected $fillable = [
        'name','role_id'
    ];
}
