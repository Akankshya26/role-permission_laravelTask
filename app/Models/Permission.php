<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = ['name', 'description', 'is_active', 'created_by'];

    //relationship between ModulePermission and permission
    public function modulepermissions()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }
    //reltionship between roles and permission
    public function roles()
    {
        return $this->belongsTo(Role::class);
    }
}
