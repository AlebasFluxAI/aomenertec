<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotovoltaicPrice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "network_operator_id",
        "stratum_id",
        "subsidy",
        "price",
        "credit",
    ];

    public function network_operator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function stratum()
    {
        return $this->belongsTo(Stratum::class);
    }
}
