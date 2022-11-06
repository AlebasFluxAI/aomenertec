<?php

namespace App\Models;

use App\Models\Traits\PaginatorTrait;
use App\Models\V1\TabPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabPermissionUser extends Model
{
    use HasFactory;
    use PaginatorTrait;


    protected $fillable = [
        "tab_permission_id",
        "conditionable_type",
        "conditionable_id",
    ];

    public function permissionable()
    {
        return $this->morphTo();
    }

    public function conditionable()
    {
        return $this->morphTo();
    }

    public function tabPermission()
    {
        return $this->belongsTo(TabPermission::class);
    }


}
