<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\ImageableManyTrait;
use App\Models\Traits\ImageableTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    use AuditableTrait;
    use ImageableTrait;

    public const WORK_ORDER_TYPE_INSTALLATION = "installation";
    public const WORK_ORDER_TYPE_REPLACE = "replace";
    public const WORK_ORDER_TYPE_CORRECTIVE_MAINTENANCE = "corrective_maintenance";
    public const WORK_ORDER_TYPE_PREVENTIVE_MAINTENANCE = "preventive_maintenance";

    public const WORK_ORDER_STATUS_OPEN = "open";
    public const WORK_ORDER_STATUS_IN_PROGRESS = "in_progress";
    public const WORK_ORDER_STATUS_CLOSED = "closed";

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
        "support_id"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
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
        ];
    }

    public function pqr()
    {
        return $this->belongsTo(Pqr::class);
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
        return $this->belongsTo(Client::class);
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
            ]
        ];
    }
}
