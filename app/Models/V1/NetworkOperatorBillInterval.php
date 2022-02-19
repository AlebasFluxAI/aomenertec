<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkOperatorBillInterval extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        "network_operator_id",
        "interval",
        "day",
        "payment_deadline"
    ];

    public function network_operator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
}
