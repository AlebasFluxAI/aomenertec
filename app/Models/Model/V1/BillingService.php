<?php

namespace App\Models\Model\V1;

use App\Models\V1\NetworkOperator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "network_operator_id",
        "has_billable_pqr",
        "has_billable_orders",
        "has_billable_clients",
        "pqr_price",
        "orders_price",
    ];

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
}
