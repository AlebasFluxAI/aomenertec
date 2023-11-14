<?php

namespace App\Models\V1;

use App\Models\Traits\FeeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZniLevelFee extends Model
{
    use HasFactory;
    use FeeTrait;

    public const LEVEL_1_A = "level_1_a";
    public const LEVEL_1_B = "level_1_b";
    public const LEVEL_1_C = "level_1_c";
    public const LEVEL_2 = "level_2";
    public const LEVEL_3 = "level_3";
    public const LEVEL_4 = "level_4";

    public const PERCENTAGE_FEE = "percentage_fee";
    public const MONEY_FEE = "money_fee";

    protected $fillable = [
        "voltage_level_id",
        "network_operator_id",
        "generation",
        "transmission",
        "distribution",
        "commercialization",
        "lost",
        "restriction",
        "unit_cost",
        "optional_fee",
        "month",
        "year",
        "default_rate"
    ];

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function voltageLevel()
    {
        return $this->belongsTo(VoltageLevel::class);
    }
}
