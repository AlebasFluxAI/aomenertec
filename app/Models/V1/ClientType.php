<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'description',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function equipmentTypes()
    {
        return $this->belongsToMany(EquipmentType::class, 'client_type_equipment_types');
    }
}
