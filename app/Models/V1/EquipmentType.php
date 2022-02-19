<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'type','description'
    ];

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function clientTypes()
    {
        return $this->belongsToMany(Client::class, 'equipment_assignments');
    }
}
