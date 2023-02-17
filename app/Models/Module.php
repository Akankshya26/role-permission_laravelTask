<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = [
        'module_code',
        'name',
        'is_active',
        'is_in_menu',
        'created_by',
    ];
    //relationship between module and permission
    public function permissions()
    {
        return $this->belongsTo(Permission::class);
    }
}
