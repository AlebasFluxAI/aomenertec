<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_NEW = 'new';
    public const STATUS_REPAIRED = 'repaired';
    public const STATUS_REPAIR = 'repair';
    public const STATUS_DISREPAIR = 'disrepair';


    protected $fillable = [
        'id',
        "name",
        'equipment_type_id',
        'serial',
        'description',
        'status',
        'assigned',
        'admin_id',
        'network_operator_id',
        'technician_id',
    ];

    public static function getModelAsKeyValue()
    {
        return (array_merge([[
            "key" => "Seleccione el tipo de equipo ...",
            "value" => null
        ]], (parent::whereNull("admin_id")
            ->with("equipmentType")
            ->orderBy("serial", "asc")
            ->get()->map(function ($equipment) {
                return [
                    "key" => $equipment->id . "- " . $equipment->equipmentType->type . "- " . $equipment->serial,
                    "value" => $equipment->id,
                ];
            }))->toArray()));
    }


    public function clients()
    {
        return $this->belongsToMany(Client::class, 'equipment_clients')
            ->withPivot('current_assigned');
    }


    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function networkOperators()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function technicians()
    {
        return $this->belongsTo(Technician::class);
    }

}
