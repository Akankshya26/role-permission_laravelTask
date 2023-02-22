<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use SoftDeletes;
    use Uuids;
    use HasFactory;
    protected $fillable = ['name', 'description'];
    protected $dates    = ['deleted_at'];
    //relationship between role and permission
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }
    //relationship between user and roles
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    //function for check permissions for roles
    public function roleAccess($modulepermissions, $permissions)
    {
        // dd($this->permissions()->first()->permissionAccess($modulepermissions, $permissions));
        return $this->permissions()->first()->permissionAccess($modulepermissions, $permissions);
    }
}
