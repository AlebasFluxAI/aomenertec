<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentConnectionPoint extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "client_id",
        "equipment_id",
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
