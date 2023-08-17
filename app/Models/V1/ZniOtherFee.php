<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZniOtherFee extends Model
{
    use HasFactory;

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
