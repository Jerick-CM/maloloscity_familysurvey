<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoloParent_renewal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "soloparent_renewals";

    protected $fillable = [
        'id',
        'soloparent_id',
        'year',
        'date_of_application'
    ];

    public function soloparentaccount()
    {
        return $this->belongsTo(SoloParent::class);
    }
}
