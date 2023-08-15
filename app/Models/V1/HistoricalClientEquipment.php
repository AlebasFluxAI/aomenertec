<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoricalClientEquipment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    protected $table = "historical_client_equipments";
    protected $fillable = [
        "client_id",
        "before_equipment_id",
        "equipment_id",
        "pqr_id",
        "assigned_by_id",
        "assigned_by_model",
        "notes",
        "work_order_id"
    ];


    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo($this->assigned_by_model);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function pqr()
    {
        return $this->belongsTo(Pqr::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function beforeEquipment()
    {
        return $this->belongsTo(Equipment::class, "before_equipment_id");
    }

    public function getCreationDate()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
    }
}
