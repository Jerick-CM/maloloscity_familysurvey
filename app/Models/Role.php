<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    // use HasFactory, SoftDeletes;
    use HasFactory;
    protected $table = "roles";
    public $timestamps = true;

    /* role assignment  */
    /* note: role 1 and 2 are reserved for administration  */
    /* note: role 3 is for account users only  */
    /* note: role 4 onwards is for new role administrators to create checklists  */

    const SUPERADMIN = 1;
    const ADMIN = 2;

    const USER = 3;
    
    const BPLO = 4;
    const BFP = 5;
    // const newRoleAssignments  = 6; 6 onwards
    

    protected $fillable = [
        'name',
    ];

    public function user_roles()
    {
        return $this->hasMany(Role_Users::class, 'role_id');
    }
}
