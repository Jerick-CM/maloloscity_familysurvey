<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Logs extends Model
{
    protected $table = "logs";
    const TYPE_CREATE_ITINERARY = 0;
    const TYPE_UPDATE_ITINERARY = 1;
    const TYPE_DELETE_ITINERARY = 2;
    const TYPE_DELETE_ITINERARY_SELECTED = 3;

    const TYPE_LOGIN = 4;
    const TYPE_LOGOUT = 5;

    const TYPE_DELETE_USER = 6;
    const TYPE_CREATE_USER = 7;
    const TYPE_UPDATE_USER = 8;
    const TYPE_CHANGEPASSWORD_USER = 9;
    const TYPE_RESETPASSWORD_USER = 10;

    const TYPE_DELETE_BUSINESS = 11;
    const TYPE_CREATE_BUSINESS = 12;
    const TYPE_UPDATE_BUSINESS = 13;

    const TYPE_UPDATE_CHECKLIST = 14;
    const TYPE_UPDATE_ROLES = 15;


    protected $fillable = [
        'user_id',
        'type',
        'meta',
    ];

    protected $appends = [
        'description', 'type_desc', 'username', 'useremail', 'createddate'
    ];

    public function getcreateddateAttribute()
    {
        return Carbon::parse($this->requestdate)->dayName . " " . Carbon::parse($this->requestdate)->isoFormat(', MMM Do YYYY ');
    }
    public function getUserEmailAttribute()
    {
        return  User::select('email')->where('id', $this->user_id)->first()['email'];
    }
    public function getUserNameAttribute()
    {
        return  User::select('name')->where('id', $this->user_id)->first()['name'];
    }

    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }

    public function setMetaAttribute($value)
    {
        $this->attributes['meta'] = json_encode($value);
    }


    public function getTypeDescAttribute($value)
    {
        switch ($this->attributes['type']) {
            case 0:
                $result = 'Created an Itinerary';
                break;
            case 1:
                $result = 'Update an Itinerary';
                break;
            case 2:
                $result = 'Delete an Itinerary';
                break;
            case 3:
                $result = 'Delete multiple itineraries';
                break;
            case 4:
                $result = 'User Login';
                break;
            case 5:
                $result = 'User Logout';
                break;
            case 6:
                $result = 'Delete User';
                break;
            case 7:
                $result = 'Create User';
                break;
            case 8:
                $result = 'Update User';
                break;
            case 9:
                $result = 'Change Password User';
                break;
            case 10:
                $result = 'Reset Password User';
                break;


            case 11:
                $result = 'Delete Business';
                break;
            case 12:
                $result = 'Create Business';
                break;
            case 13:
                $result = 'Update Business';
                break;

            case 14:
                $result = 'Update Checklist';
                break;
            case 15:
                $result = 'Update Roles';
                break;
        }
        return $result;
    }

    public function getDescriptionAttribute()
    {
        switch ($this->attributes['type']) {
            case 0:
                $result = __('logs.itinerary.create', json_decode($this->attributes['meta'], true));
                break;
            case 1:
                $result = __('logs.itinerary.update', json_decode($this->attributes['meta'], true));
                break;
            case 2:
                $result = __('logs.itinerary.delete', json_decode($this->attributes['meta'], true));
                break;
            case 3:
                $result = __('logs.itinerary.delete_multiple', json_decode($this->attributes['meta'], true));
                break;
            case 4:
                $result = __('logs.user.login', json_decode($this->attributes['meta'], true));
                break;
            case 5:
                $result = __('logs.user.logout', json_decode($this->attributes['meta'], true));
                break;
            case 6:
                $result = __('logs.user.delete', json_decode($this->attributes['meta'], true));
                break;
            case 7:
                $result = __('logs.user.create', json_decode($this->attributes['meta'], true));
                break;
            case 8:
                $result = __('logs.user.update', json_decode($this->attributes['meta'], true));
                break;
            case 9:
                $result = __('logs.user.changepassword', json_decode($this->attributes['meta'], true));
                break;
            case 10:
                $result = __('logs.user.resetpassword', json_decode($this->attributes['meta'], true));
                break;

            case 11:
                $result = __('logs.business.delete', json_decode($this->attributes['meta'], true));
                break;
            case 12:
                $result = __('logs.business.create', json_decode($this->attributes['meta'], true));
                break;
            case 13:
                $result = __('logs.business.update', json_decode($this->attributes['meta'], true));
                break;

            case 14:
                $result = __('logs.checklist.update', json_decode($this->attributes['meta'], true));
                break;
            case 15:
                $result = __('logs.roles.update', json_decode($this->attributes['meta'], true));
                break;
        }

        return $result;
    }
}
