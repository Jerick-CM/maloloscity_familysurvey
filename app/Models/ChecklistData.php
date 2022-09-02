<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;



class ChecklistData extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id','itinerary_businesss_id','checkbox','label','index','business_id','itinerary_id'];
    protected $table = "checklistdata";
    protected $primaryKey = 'id';
}
