<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaupesClientStratificationFee extends Model
{
    use HasFactory;

    protected $fillable = [
        "network_operator_id",
        "residence_1_41r",
        "residence_2_42r",
        "residence_3_43r",
        "official_1_410",
        "official_2_420",
        "commercial_1_41c",
        "commercial_2_42c",
        "commercial_3_43c",
        "suspended_r1_r2",
        "month",
        "year",
        "client_type_id"
    ];

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
}
