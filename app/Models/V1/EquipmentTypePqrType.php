<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentTypePqrType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'equipment_type_id',
        'pqr_type_id'
    ];

}
