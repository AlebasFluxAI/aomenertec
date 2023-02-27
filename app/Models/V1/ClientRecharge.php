<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientRecharge extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const PURCHASE_TYPE_ONLINE = 'purchase_type_online';
    public const PURCHASE_TYPE_STORE = 'purchase_type_store';

    public const PURCHASE_PAYMENT_METHOD_CASH = "payment_method_cash";
    public const PURCHASE_PAYMENT_METHOD_ONLINE = "payment_method_online";

    public const PURCHASE_PAYMENT_STATUS_PENDING = "payment_status_pending";
    public const PURCHASE_PAYMENT_STATUS_COMPLETED = "payment_status_completed";
    public const PURCHASE_PAYMENT_STATUS_CANCELLED = "payment_status_cancelled";

    protected $fillable = [
        "client_id",
        "network_operator_id",
        "seller_id",
        "kwh_price",
        "kwh_credit",
        "kwh_subsidy",
        "kwh_quantity",
        "total",
        "reference",
        "purchase_type",
        "payment_method",
        "payment_status",
        "notified",
        "recharge_code",
        "consecutive"
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
