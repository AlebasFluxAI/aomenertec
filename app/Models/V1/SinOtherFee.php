<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SinOtherFee extends Model
{
    use HasFactory;

    public const PERCENTAGE_FEE = "percentage_fee";
    public const MONEY_FEE = "money_fee";

    protected $fillable = [
        "network_operator_id",
        "strata_id",
        "tax_type",
        "contribution",
        "discount",
        "tax",
        "month",
        "year",
    ];
}
