<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillableItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;

    public const CONTRIBUTION_ITEM = "contribution";
    public const DISCOUNT_ITEM = "discount";
    public const PUBLIC_TAX_ITEM = "public_item";
    public const PUBLIC_TAX_TYPE_ITEM = "public_item_type";
    public const PUBLIC_TAX_TYPE_TOTAL = "public_item_total";
    public const COMMERCIALIZATION_ITEM = "commercialization_item";
    public const DISTRIBUTION_ITEM = "distribution_item";
    public const RESTRICTION_ITEM = "restriction_item";
    public const GENERATION_ITEM = "generation_item";
    public const TRANSMISSION_ITEM = "transmission_item";
    public const LOST_ITEM = "lost_item";
    public const TOTAL_CONSUMPTION = "total_consumption";
    public const TOTAL_CONSUMPTION_BASE = "total_consumption_base";
    public const TOTAL_WITH_SUB = "total_with_sub";
    public const TOTAL_WITHOUT_SUB = "total_without_sub";
    public const TOTAL_INVOICE = "total_invoice";
    public const PQR_ISSUED = "pqr_issued";
    public const WORK_ORDER = "work_order";
    public const PQR_ISSUED_INITIAL = "pqr_issued_initial";
    public const WORK_ORDER_INITIAL = "work_order_initial";


    protected $fillable = [
        "name",
        "description",
        "code",
        "tax_id",
        "slug"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
