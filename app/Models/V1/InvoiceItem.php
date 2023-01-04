<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "unit_total",
        "subtotal",
        "total",
        "tax_total",
        "discount",
        "billable_item_id",
        "quantity",
        "notes"
    ];

    public function billableItem()
    {
        return $this->belongsTo(BillableItem::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
