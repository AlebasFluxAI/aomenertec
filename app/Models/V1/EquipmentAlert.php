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
        "type",
        "interval",
        "equipment_id",
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
