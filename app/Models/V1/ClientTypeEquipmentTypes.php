<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientTypeEquipmentTypes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'equipment_type_id',
        'client_type_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }
}
