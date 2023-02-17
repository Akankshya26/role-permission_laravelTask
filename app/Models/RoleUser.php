<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = ['role_id', 'user_id'];
}
