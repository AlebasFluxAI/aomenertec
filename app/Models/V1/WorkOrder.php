<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Scope\ClientEnabledScope;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkOrder extends Model
{
    use HasFactory;
    use AuditableTrait;
    use ImageableTrait;
    use PaginatorTrait;


    public const WORK_ORDER_TYPE_INSTALLATION = "installation";
    public const WORK_ORDER_TYPE_DISCONNECTION = "disconnection";
    public const WORK_ORDER_TYPE_READING = "reading";
    public const WORK_ORDER_TYPE_RECONNECTION = "reconnection";
    public const WORK_ORDER_TYPE_REPLACE = "replace";
    public const WORK_ORDER_TYPE_CORRECTIVE_MAINTENANCE = "corrective_maintenance";
    public const WORK_ORDER_TYPE_PREVENTIVE_MAINTENANCE = "preventive_maintenance";
    public const WORK_ORDER_TYPE_DISABLE_CLIENT = "disable_client";
    public const WORK_ORDER_TYPE_ENABLE_CLIENT = "enable_client";
    public const WORK_ORDER_TYPE_PQR = "pqr";

    public const WORK_ORDER_STATUS_OPEN = "open";
    public const WORK_ORDER_STATUS_IN_PROGRESS = "in_progress";
    public const WORK_ORDER_STATUS_CLOSED = "closed";

    public const WORK_ORDER_LEVEL_1 = "level_1";
    public const WORK_ORDER_LEVEL_2 = "level_2";


    protected $fillable = [
        "client_id",
        "type",
        "created_by_type",
        "created_by_id",
        "technician_id",
        "status",
        "description",
        "solution_description",
        "pqr_id",
        "materials",
        "tools",
        "days",
        "hours",
        "minutes",
        "open_at",
        "in_progress_at",
        "closed_at",
        "open_by",
        "in_progress_by",
        "closed_by",
        "support_id",
        "taken",
        "level",
        "execution_time_hours",
        "execution_time_minutes",
        "microcontroller_data_id",
        "final_recommendations",


    ];

    public static function createFromPqr(Pqr $pqr)
    {
        return DB::transaction(function () use ($pqr) {
            return WorkOrder::create([
                "description" => $pqr->description,
                "client_id" => $pqr->client_id,
                "pqr_id" => $pqr->id,
                "status" => WorkOrder::WORK_ORDER_STATUS_OPEN,
                "level" => WorkOrder::WORK_ORDER_LEVEL_2,
                "taken" => false,
                "type" => WorkOrder::WORK_ORDER_TYPE_PQR
            ]);
        });

    }

    public static function getWorkOrderMaterials()
    {
        return [
            ["value" => "Cable", "key" => "Cable"],
            ["value" => "Alambre", "key" => "Alambre"],
            ["value" => "Conectores solares", "key" => "Conectores solares"],
            ["value" => "Otros", "key" => "Otros"],
        ];
    }

    public static function getWorkOrderTools()
    {
        return [
            ["value" => "Pinza voltiamperimetrica", "key" => "Pinza voltiamperimetrica"],
            ["value" => "Multimetro", "key" => "Multimetro"],
            ["value" => "Juego de llaves", "key" => "Juego de llaves"],
            ["value" => "Juego de destornilladores", "key" => "Juego de destornilladores"],
            ["value" => "Elementos EPP", "key" => "Elementos EPP"],
            ["value" => "Elementos adicionales", "key" => "Elementos adicionales"],
            ["value" => "Llaves de apertura de gabinete", "key" => "Llaves de apertura de gabinete"],
            ["value" => "Otras", "key" => "Otras"],
        ];
    }

    static public function indexTableHeaders()
    {
        return [
            [
                "col_name" => "ID",
                "col_data" => "id",
                "col_filter" => false
            ],
            [
                "col_name" => "Cliente",
                "col_data" => "client.name",
                "col_filter" => false
            ],
            [
                "col_name" => "Alias",
                "col_data" => "client.alias",
                "col_filter" => false
            ],
            [
                "col_name" => "Tipo",
                "col_translate" => "work_order",
                "col_data" => "type",
                "col_filter" => false
            ],
            [
                "col_name" => "Estado",
                "col_translate" => "work_order",
                "col_data" => "status",
                "col_filter" => false
            ],
            [
                "col_name" => "Descripción",
                "col_data" => "description",
                "col_filter" => false
            ],
            [
                "col_name" => "Materiales",
                "col_data" => "materials",
                "col_filter" => false
            ],
            [
                "col_name" => "Herramientas",
                "col_data" => "tools",
                "col_filter" => false
            ],
            [
                "col_name" => "Nivel",
                "col_translate" => "work_order",
                "col_data" => "level",
                "col_filter" => false
            ],

        ];
    }

    public static function getTypeAsKeyValue()
    {
        return [
            [
                "value" => self::WORK_ORDER_TYPE_REPLACE,
                "key" => __("work_order." . self::WORK_ORDER_TYPE_REPLACE)
            ],
            [
                "value" => self::WORK_ORDER_TYPE_INSTALLATION,
                "key" => __("work_order." . self::WORK_ORDER_TYPE_INSTALLATION)
            ],
            [
                "value" => self::WORK_ORDER_TYPE_PREVENTIVE_MAINTENANCE,
                "key" => __("work_order." . self::WORK_ORDER_TYPE_PREVENTIVE_MAINTENANCE)
            ],
            [
                "value" => self::WORK_ORDER_TYPE_CORRECTIVE_MAINTENANCE,
                "key" => __("work_order." . self::WORK_ORDER_TYPE_CORRECTIVE_MAINTENANCE)
            ],
            [
                "value" => self::WORK_ORDER_TYPE_DISABLE_CLIENT,
                "key" => __("work_order." . self::WORK_ORDER_TYPE_DISABLE_CLIENT)
            ]
        ];
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
                        "route" => "administrar.v1.ordenes_de_servicio.detalle",
                        "binding" => "workOrder",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-search",
                    "tooltip_title" => "Detalles",
                    "permission" => [\App\Http\Resources\V1\Permissions::WORK_ORDER_DETAILS],
                ],
            ],
            [
                "title" => "Gestionar",
                "actionable" => [
                    "redirect" => [
                        "route" => "administrar.v1.ordenes_de_servicio.administrar",
                        "binding" => "workOrder",
                        "value" => $this->id
                    ],
                    "conditional" => "adminWorkOrderConditional",
                    "icon" => "fas fa-toolbox",
                    "tooltip_title" => "Gestionar",
                    "permission" => [\App\Http\Resources\V1\Permissions::WORK_ORDER_SOLVE],
                ]
            ],
            [
                "title" => "Editar",
                "actionable" => [

                    "redirect" => [
                        "route" => "administrar.v1.ordenes_de_servicio.editar",
                        "binding" => "workOrder",
                        "value" => $this->id

                    ],
                    "icon" => "fas fa-pencil",
                    "tooltip_title" => "Editar",
                    "conditional" => "conditionalTypeReading",
                    "permission" => [\App\Http\Resources\V1\Permissions::WORK_ORDER_EDIT],
                ]
            ],

            [
                "title" => "Registrar lectura",
                "actionable" => [
                    "redirect" => [
                        "route" => "v1.admin.client.hand_reading.crear",
                        "binding" => "workOrder",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-file-signature",
                    "tooltip_title" => "Registrar lectura",
                    "conditional" => "conditionalManuallyCreate",
                    "permission" => [\App\Http\Resources\V1\Permissions::CLIENT_HAND_READING_CREATE],
                ],
            ]
        ];

    }

    public function createdBy()
    {
        return User::find($this->created_by_id);
    }

    public function closedBy()
    {
        return User::find($this->closed_by);
    }

    public function setInProgress()
    {
        $this->update([
            "status" => WorkOrder::WORK_ORDER_STATUS_IN_PROGRESS
        ]);
    }

    public function support()
    {
        return $this->belongsTo(Support::class);
    }

    public function equipments()
    {
        return $this->hasMany(WorkOrderEquipment::class);
    }

    public function equipment()
    {
        return $this->hasMany(WorkOrderEquipment::class)->first();
    }

    public function pqr()
    {
        return $this->belongsTo(Pqr::class);
    }

    public function microcontrollerData()
    {
        return $this->belongsTo(MicrocontrollerData::class);
    }

    public function setOpen()
    {
        $this->update([
            "status" => WorkOrder::WORK_ORDER_STATUS_OPEN
        ]);
    }

    public function evidences()
    {
        return $this->morphMany(Image::class, "imageable")->whereType("evidences");
    }

    public function images()
    {
        return $this->morphMany(Image::class, "imageable")->whereType("images");
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withoutGlobalScope(ClientEnabledScope::class);
    }
}
