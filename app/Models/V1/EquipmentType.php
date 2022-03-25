<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'id','type','description'
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    public function clientTypes()
    {
        return $this->belongsToMany(ClientType::class, 'client_type_equipment_types');
    }
    public function pqrTypes()
    {
        return $this->belongsToMany(PqrType::class, 'equipment_type_pqr_types');
    }

}
