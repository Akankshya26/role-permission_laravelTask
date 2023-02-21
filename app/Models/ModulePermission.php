<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModulePermission extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = [
        'permission_id',
        'module_id',
        'add_access',
        'edit_access',
        'delete_access',
        'view_access'
    ];
    protected $dates = ['deleted_at'];
}
