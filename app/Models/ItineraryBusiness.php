<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ItineraryBusiness extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['itinerary_id', 'business_id', 'status', 'data', 'check1', 'check2', 'check3', 'check4', 'check5', 'check6', 'check7', 'check8', 'check9', 'check10', 'remarks', 'start_at', 'end_at', 'completed_time'];
    protected $table = "itinerary_businesses";
    protected $appends = ['completeddate', 'startdate', 'createddate', 'checklistdata'];

    public function getCompletedDateAttribute()
    {
        return Carbon::parse($this->completed_time)->isoFormat('h:mm:ss a') . " - " . Carbon::parse($this->completed_time)->dayName . " " . Carbon::parse($this->completed_time)->isoFormat(' MMM Do YYYY ');
    }

    public function getStartDateAttribute()
    {
        return Carbon::parse($this->start_at)->isoFormat('h:mm:ss a') . " - " . Carbon::parse($this->start_at)->dayName . " " . Carbon::parse($this->start_at)->isoFormat(' MMM Do YYYY ');
    }

    public function getCreatedDateAttribute()
    {
        $itinerary = Itinerary::select('created_at')->where('id', $this->itinerary_id)->first();
        return Carbon::parse($itinerary->created_at)->isoFormat('h:mm:ss a') . " - " . Carbon::parse($itinerary->created_at)->dayName . " " . Carbon::parse($itinerary->created_at)->isoFormat('MMM Do YYYY ');
    }

    public function getChecklistDataAttribute()
    {
        $checklistdata = ChecklistData::select('id', 'checkbox', 'label', 'index')->where('itinerary_businesss_id', $this->id)->get();
        return $checklistdata;
    }
}
