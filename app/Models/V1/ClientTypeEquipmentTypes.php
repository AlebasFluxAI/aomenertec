<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientTypeEquipmentTypes extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;


    protected $fillable = [
        'equipment_type_id',
        'client_type_id',
    ];
}
