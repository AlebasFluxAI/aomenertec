<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;
    public const STATUS_NEW = 'new';
    public const STATUS_REPAIRED = 'repaired';
    public const STATUS_REPAIR = 'repair';
    public const STATUS_DISREPAIR = 'disrepair';


    protected $fillable = [
        "name",
        'equipment_type_id',
        'serial',
        'description',
        'status',
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

    public function alerts()
    {
        return $this->hasMany(EquipmentAlert::class, "equipments_id");
    }
}
