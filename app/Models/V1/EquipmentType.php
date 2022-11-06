<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    protected $fillable = [
        'id', 'type', 'description'
    ];

    public static function getModelAsKeyValue()
    {
        return (array_merge([[
            "key" => "Seleccione el tipo de equipo ...",
            "value" => null
        ]], (parent::get()->map(function ($equipmentType) {
            return [
                "key" => $equipmentType->id . "- " . $equipmentType->type,
                "value" => $equipmentType->id,
            ];
        }))->toArray()));
    }

    public function superAdmin()
    {
        return $this->belongsTo(SuperAdmin::class);
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    public function clientTypes()
    {
        return $this->belongsToMany(ClientType::class, 'client_type_equipment_types');
    }

    public function pqrTypes()
    {
        return $this->belongsToMany(PqrType::class, 'equipment_type_pqr_types');
    }
}
