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

    public const COP = 'cop';
    public const USD = 'usd';

    protected $fillable = [
        "network_operator_id",
        "has_billable_pqr",
        "has_billable_orders",
        "has_billable_clients",
        "pqr_price",
        "orders_price",
        "initial_package_pqr_price",
        "initial_package_orders_price",
        "currency",
        "min_clients",
        "min_client_value",
        "pqr_initial_bag",
        "work_order_initial_bag",
        "billing_day"
    ];

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
}
