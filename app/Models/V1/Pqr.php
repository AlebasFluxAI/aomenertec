<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pqr extends Model
{
    use HasFactory;
    use ImageableTrait;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;

    public const STATUS_CREATED = 'created';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    public const PQR_TYPE_BILLING = "type_billing";
    public const PQR_TYPE_PLATFORM = "type_platform";
    public const PQR_TYPE_TECHNICIAN = "type_technicians";

    public const PQR_SUB_TYPE_OVERRUN = "sub_type_overrun";
    public const PQR_SUB_TYPE_INVOICING = "sub_type_invoicing";
    public const PQR_SUB_TYPE_PAYMENT_AGREE = "sub_type_payment_agree";

    public const PQR_SUB_TYPE_PLATFORM_ADMIN = "sub_type_platform_admin";

    public const PQR_SUB_TYPE_ERROR = "sub_type_error";

    public const PQR_SEVERITY_LOW = "severity_low";
    public const PQR_SEVERITY_MEDIUM = "severity_medium";
    public const PQR_SEVERITY_HIGH = "severity_high";

    public const PQR_LEVEL_1 = "level_1";
    public const PQR_LEVEL_2 = "level_2";


    protected $fillable = [
        'detail',
        'equipment_id',
        'pqr_type_id',
        'network_operator_id',
        'technician_id',
        'user_id',
        'client_id',
        'support_id',
        'status',
        'subject',
        'description',
        'level',
        'type',
        'sub_type',
        'severity',
        'contact_name',
        'contact_phone',
        'contact_identification',
        'client_code',
        "code",
        "supervisor_id",
        "taken",
        "change_equipment",
        "has_equipment_changed",
        "status_" . self::STATUS_CREATED . "_at",
        "status_" . self::STATUS_PROCESSING . "_at",
        "status_" . self::STATUS_RESOLVED . "_at",
        "status_" . self::STATUS_CLOSED . "_at",
        "status_" . self::STATUS_CREATED . "_by",
        "status_" . self::STATUS_PROCESSING . "_by",
        "status_" . self::STATUS_RESOLVED . "_by",
        "status_" . self::STATUS_CLOSED . "_by",
    ];

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
                "col_name" => "Tipo",
                "col_translate" => "pqr",
                "col_data" => "type",
                "col_filter" => false
            ],
            [
                "col_name" => "Estado",
                "col_translate" => "pqr",
                "col_data" => "status",
                "col_filter" => false
            ],
            [
                "col_name" => "Descripción",
                "col_data" => "description",
                "col_filter" => false
            ],
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
                        "route" => "administrar.v1.peticiones.detalles",
                        "binding" => "pqr",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-search",
                    "tooltip_title" => "Detalles",
                    "permission" => [\App\Http\Resources\V1\Permissions::PQR_SHOW],
                ],
            ],

            [
                "title" => "Responder ticket",
                "actionable" => [
                    "permission" => [\App\Http\Resources\V1\Permissions::PQR_REPLY],
                    "redirect" => [
                        "route" => "administrar.v1.peticiones.respuesta",
                        "binding" => "pqr",
                        "value" => $this->id
                    ],
                    "icon" => "fa fa-comment-dots",
                    "tooltip_title" => "Responder ticket",
                    "conditional" => "openTicked"
                ],
            ],
            [
                "title" => "Historial de mensajes",
                "actionable" => [
                    "permission" => [\App\Http\Resources\V1\Permissions::PQR_REPLY],
                    "redirect" => [
                        "route" => "administrar.v1.peticiones.historial-mensajes",
                        "binding" => "pqr",
                        "value" => $this->id
                    ],
                    "icon" => "fa fa-list",
                    "tooltip_title" => "Historial de mensajes",
                ],
            ],
            [
                "title" => "Historial de cambios de equipo",
                "actionable" => [
                    "permission" => [\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE_MANAGE],
                    "redirect" => [
                        "route" => "administrar.v1.peticiones.cambio-equipo-historico",
                        "binding" => "pqr",
                        "value" => $this->id
                    ],
                    "icon" => "fa fa-server",
                    "tooltip_title" => "Historial de cambios de equipo",
                    "conditional" => "closedTicked"
                ],
            ],
            [
                "title" => "Relacionar cliente",
                "actionable" => [
                    "permission" => [\App\Http\Resources\V1\Permissions::PQR_LINK_CLIENT],
                    "redirect" => [
                        "route" => "administrar.v1.peticiones.relacionar_cliente",
                        "binding" => "pqr",
                        "value" => $this->id
                    ],
                    "icon" => "fa fa-user-plus",
                    "tooltip_title" => "Relacionar cliente",
                ],
            ],
            [
                "title" => "Gestionar cambio de equipo",
                "permission" => [\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE_MANAGE],
                "function" => "requestEquipment",
                "icon" => "fas fa-rotate",
                "tooltip_title" => "Gestionar cambio de equipo",
                "actionable" => [
                    "conditional" => "equipmentRequest",
                    "redirect" => [
                        "route" => "administrar.v1.peticiones.cambio-equipo",
                        "binding" => "pqr",
                        "value" => $this->id
                    ],
                ],
            ],
            [
                "title" => "Resolver ticket",
                "actionable" => [
                    "permission" => [\App\Http\Resources\V1\Permissions::PQR_CLOSE],
                    "redirect" => [
                        "route" => "administrar.v1.peticiones.cierre",
                        "binding" => "pqr",
                        "value" => $this->id
                    ],
                    "icon" => "fas fa-check",
                    "tooltip_title" => "Resolver ticket",
                    "conditional" => "openTicked"
                ],
            ],
        ];
    }

    public function hasClient()
    {
        return $this->client != null;
    }

    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function support()
    {
        return $this->belongsTo(Support::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function messages()
    {
        return $this->hasMany(PqrMessage::class)->whereType(PqrMessage::MESSAGE_TYPE_REGULAR);
    }

    public function messagesFile()
    {
        $messages = $this->messages;
        $images = [];
        foreach ($messages as $index => $message) {
            if ($message->attach) {
                $images[$index] = $message->attach->url;
            }
        }
        return $images;
    }

    public function closeMessage()
    {
        return $this->hasOne(PqrMessage::class)->whereType(PqrMessage::MESSAGE_TYPE_CLOSER);
    }

    public function pqrLogs()
    {
        return $this->hasMany(PqrLog::class);
    }

    public function attach()
    {
        return $this->morphOne(Image::class, "imageable");
    }

    public function setEquipmentChanged()
    {
        $this->update([
            "has_equipment_changed" => true,
            "change_equipment" => false]);
    }

    public function pqrUsers()
    {
        return $this->hasMany(PqrUser::class);
    }

    public function logs()
    {
        return $this->hasMany(PqrLog::class);
    }

    public function senderType()
    {
        if ($this->networkOperator) {
            return "Usuario operador de red";
        }
        if ($this->supervisor) {
            return "Usuario supervisor";
        }
        return "Cliente";
    }

    public function equipmentChangeHistorical()
    {
        return $this->hasMany(HistoricalClientEquipment::class);
    }

    public function sender()
    {
        if ($this->support) {
            return $this->support;
        }
        if ($this->networkOperator) {
            return $this->networkOperator;
        }
        if ($this->supervisor) {
            return $this->supervisor;
        }
        return $this->client;
    }
}
