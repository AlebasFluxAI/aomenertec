<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentClient extends Model
{
    use HasFactory;
    use AuditableTrait;

    public $incrementing = true;

    protected $fillable = [
        'client_id',
        'equipment_id',
        'current_assigned'
    ];
}
