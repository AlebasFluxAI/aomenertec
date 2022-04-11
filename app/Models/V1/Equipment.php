<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_NEW = 'new';
    public const STATUS_REPAIRED = 'repaired';
    public const STATUS_REPAIR = 'repair';
    public const STATUS_DISREPAIR = 'disrepair';


    protected $fillable = [
        'id',
        "name",
        'equipment_type_id',
        'serial',
        'description',
        'status',
        'assigned',
    ];
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'equipment_clients')
            ->withPivot('current_assigned');
    }
    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }


}
