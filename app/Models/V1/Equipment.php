<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;
    protected $table="equipments";
    protected $fillable=[
        "name",
        'equipment_type_id',
        'serial',
        'description',
        'equipment_condition_id',
        'assigned',
    ];

    public function equipment_type()
    {
        return $this->belongsTo(EquipmentType::class);
    }
    public function equipment_condition()
    {
        return $this->hasOne(EquipmentCondition::class);
    }
}
