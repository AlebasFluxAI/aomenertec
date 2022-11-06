<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabPermissionAdmin extends Model
{
    use HasFactory;
    use PaginatorTrait;


    protected $fillable = [
        "tab_permission_id",
        "admin_id",
        "enabled"
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function tabPermission()
    {
        return $this->belongsTo(TabPermission::class);
    }

    public function blinkPermission()
    {
        $this->update([
            "enabled" => !$this->enabled,
        ]);
    }
}
