<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentClient extends Model
{
    use HasFactory;
    use AuditableTrait;
    use PaginatorTrait;


    public $incrementing = true;

    protected $fillable = [
        'client_id',
        'equipment_id',
        'current_assigned'
    ];
}
