<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function Equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function clientTypes()
    {
        return $this->belongsToMany(Client::class, 'equipment_assignments');
    }
}
