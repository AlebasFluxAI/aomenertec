<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Agregasr estato pendiente de reparacion
    public const STATUS_NEW = 'new';
    public const STATUS_REPAIRED = 'repaired'; //  Reparado.
    public const STATUS_REPAIR = 'repair'; // En reparacion.
    public const STATUS_DISREPAIR = 'disrepair'; // Para dar de baja.
    public const STATUS_REPAIR_PENDING = 'repair_pending'; // Para dar de baja.
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
        'has_admin',
        'has_network_operator',
        'has_technician',
        'has_clients',
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

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'equipment_clients')
            ->withPivot('current_assigned')
            ->whereNull("equipment_clients.deleted_at");
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

    public function getNameSerial()
    {
        return $this->serial . " - " . $this->namess;
    }
}
