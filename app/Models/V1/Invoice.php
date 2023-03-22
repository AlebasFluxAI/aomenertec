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

    public const PAYMENT_STATUS_PENDING = "pending";
    public const PAYMENT_STATUS_PAID = "paid";
    public const PAYMENT_STATUS_LATE = "late";

    protected $fillable = [
        "admin_id",
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

    ];

    public function getAdminNameAttribute()
    {
        if ($this->admin) {
            return $this->admin->name;
        }
        return "";

    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }
}
