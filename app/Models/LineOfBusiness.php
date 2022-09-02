<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineOfBusiness extends Model
{
    protected $table = "line_of_business";
    use HasFactory;
    protected $fillable = [
        'type',
    ];
}
