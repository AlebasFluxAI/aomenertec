<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminEquipmentType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "admin_id",
        "equipment_type_id"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
