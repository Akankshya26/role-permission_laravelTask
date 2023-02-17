<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = ['name', 'description'];
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
}
