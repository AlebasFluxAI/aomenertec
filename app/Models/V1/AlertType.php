<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertType extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE_VOLTAGE = "voltage";
    const TYPE_CURRENT = "current";
    protected $fillable = [
        "types"
    ];

    public function equipmentAlerts()
    {
        return $this->hasMany(EquipmentAlert::class);
    }
}
