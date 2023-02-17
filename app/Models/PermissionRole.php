<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionRole extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = [
        'role_id',
        'permission_id',
    ];
}
