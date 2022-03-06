<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentAlert extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE_ALERT = "alert";

    protected $fillable = [
        "value",
        "equipments_id",
        "alert_type_id",
    ];

    public function alertType()
    {
        return $this->belongsTo(AlertType::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, "equipments_id");
    }
}
