<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itinerary extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['role_id','user_id', 'businesses', 'requestdate', 'name', 'notes', 'qr_hash', 'control_number', 'assign_id', 'completed_time'];
    protected $appends = ['createddate', 'requestdateformatted'];
    protected $table = "itineraries";
    protected $primaryKey = 'id';

    public function getCreatedDateAttribute()
    {
        return Carbon::parse($this->created_at)->dayName . " " . Carbon::parse($this->created_at)->isoFormat(', MMM Do YYYY ');
    }

    public function getRequestDateFormattedAttribute()
    {
        return Carbon::parse($this->requestdate)->dayName . " " . Carbon::parse($this->requestdate)->isoFormat(', MMM Do YYYY ');
    }
}
