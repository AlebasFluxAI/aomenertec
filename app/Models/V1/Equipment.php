<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function equipmentType()
    {
        return $this->hasOne(EquipmentType::class);
    }
    public function equipmentCondition()
    {
        return $this->hasOne(EquipmentCondition::class);
    }
}
