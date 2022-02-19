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

    public function equipmentType()
    {
        return $this->hasOne(EquipmentType::class);
    }
    public function equipmentCondition()
    {
        return $this->hasOne(EquipmentCondition::class);
    }
}
