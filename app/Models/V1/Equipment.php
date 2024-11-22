<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    // Agregasr estato pendiente de reparacion
    public const STATUS_NEW = 'new';
    public const STATUS_REPAIRED = 'repaired'; //  Reparado.
    public const STATUS_REPAIR = 'repair'; // En reparacion.
    public const STATUS_DISREPAIR = 'disrepair'; // Para dar de baja.
    public const STATUS_REPAIR_PENDING = 'repair_pending'; // Pendiente de reparacion
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
        "has_multiple_connection"
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

    public function navigatorDropdownOptions()
    {
        return [
            [
                "title" => "Detalles",
                "actionable" => [
                    "redirect" => [
                        "route" => "administrar.v1.equipos.detalle",
                        "binding" => "equipment",
                        "value" => $this->id,
                    ],
                    "icon" => "fas fa-search",
                    "tooltip_title" => "Detalles",
                    "permission" => [\App\Http\Resources\V1\Permissions::EQUIPMENT_SHOW],
                ]
            ],
            [
                "title" => "Editar",
                "actionable" => [
                    "redirect" => [
                        "route" => "administrar.v1.equipos.editar",
                        "binding" => "equipment",
                        "value" => $this->id,
                    ],
                    "icon" => "fas fa-pencil",
                    "tooltip_title" => "Editar",
                    "permission" => [\App\Http\Resources\V1\Permissions::EQUIPMENT_EDIT],
                ]
            ],
        ];
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'equipment_clients', 'equipment_id', 'client_id')
            ->withPivot('current_assigned')
            ->whereNull("equipment_clients.deleted_at");
    }

    public function canDeprecate()
    {
        return ($this->status == self::STATUS_REPAIR_PENDING and !$this->has_client);
    }

    public function getHasClientAttribute()
    {
        return EquipmentClient::whereEquipmentId($this->id)->exists();
    }

    public function getAvailableAttribute()
    {
        if ($this->status == self::STATUS_DISREPAIR) {
            return false;








        }

        return !$this->has_client;

    }

    public function repair()
    {
        $this->update([
            "status" => self::STATUS_REPAIRED
        ]);
    }

    public function deprecate()
    {
        $this->update([
            "status" => self::STATUS_DISREPAIR
        ]);
    }

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function networkOperator()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function technicians()
    {
        return $this->belongsTo(Technician::class);
    }

    public function getNameSerial()
    {
        return $this->serial . " - " . $this->equipmentType->type;
    }
}
