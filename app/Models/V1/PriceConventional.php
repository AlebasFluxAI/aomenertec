<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceConventional extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    protected $fillable = [
        "network_operator_id",
        "stratum_id",
        "voltage_level_id",
        "generation",
        "commercialization",
        "loss",
        "optional_rate",
        "total",
        "use_optional",
    ];

    public function network_operator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function stratum()
    {
        return $this->belongsTo(Stratum::class);
    }

    public function voltage_level()
    {
        return $this->belongsTo(VoltageLevel::class);
    }
}
