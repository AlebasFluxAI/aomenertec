<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const ZIN_CONVENTIONAL = "ZNI Convencional";
    public const SIN_CONVENTIONAL = "SIN Convencional";
    public const ZIN_PHOTOVOLTAIC = "ZNI Sistema fotovoltaico";
    public const ZIN_RURAL = "ZNI rural";
    public const MONITORING = "Monitoreo";

    protected $fillable = [
        'type',
        'description',
    ];

    public static function clientTypesAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el tipo de cliente ...",
                "value" => null
            ]],
            (self::get()->map(function ($clientType) {
                return [
                    "key" => $clientType->id . " - " . $clientType->type,
                    "value" => $clientType->id,
                ];
            }))->toArray()
        ));
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function equipmentTypes()
    {
        return $this->belongsToMany(EquipmentType::class, 'client_type_equipment_types');
    }
}
