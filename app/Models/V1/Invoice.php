<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;


    public const PAYMENT_STATUS_APPROVED = "approved";
    public const PAYMENT_STATUS_VOIDED = "voided";
    public const PAYMENT_STATUS_DECLINED = "declined";
    public const PAYMENT_STATUS_ERROR = "error";
    public const PAYMENT_STATUS_PENDING = "pending";

    public const TYPE_PLATFORM_USAGE = "platform_usage";
    public const TYPE_CONSUMPTION = "consumption";

    protected $fillable = [
        "admin_id",
        "network_operator_id",
        "client_id",
        "subtotal",
        "total",
        "tax_total",
        "discount",
        "pdf_url",
        "payment_date",
        "expiration_date",
        "payment_status",
        "code",
        "currency",
        "invoice_start",
        "invoice_end",
        "type",
        "pdf_data"

    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function paymentRecord()
    {
        return $this->hasOne(InvoicePaymentRegistration::class);
    }

    public function getAdminNameAttribute()
    {
        if ($this->admin) {
            return $this->admin->name;
        }
        return "";

    }

    public function getNetworkOperatorNameAttribute()
    {
        if ($this->networkOperator) {
            return $this->networkOperator->name;
        }
        return "";
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getModelAttribute()
    {
        return $this->admin ? $this->admin : $this->networkOperator;
    }
}
