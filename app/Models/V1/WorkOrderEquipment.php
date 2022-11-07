<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderEquipment extends Model
{
    use HasFactory;
    use PaginatorTrait;


    protected $table = "work_order_equipments";

    protected $fillable = [
        "equipment_id",
        "work_order_id"
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

}
