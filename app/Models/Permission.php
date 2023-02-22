<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use SoftDeletes;
    use Uuids;
    use HasFactory;
    protected $fillable = ['name', 'description', 'is_active', 'created_by'];
    protected $dates = ['deleted_at'];
    //hasMany relationship between ModulePermission and permission
    public function modulepermissions()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }
    //belongsToMany reltionship between roles and permission
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    //function for  check permission have perticular access in modules.
    public function permissionAccess($modulepermissions, $permissions)
    {
        // dd($modulepermissions, $permissions);
        $module = Module::where('module_code', $modulepermissions)->first();
        // dd($permissions);
        $data   = $this->modulepermissions()->where('module_id', $module->id)->where($permissions, true)->first();
        // dd($data);
        if ($data) {
            return true;
        } else {
            return false;
        }
    }
}
