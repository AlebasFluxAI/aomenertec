<?php

namespace App\Models\V1;

use App\Models\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePaymentRegistration extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ImageableTrait;


    public const PAYMENT_METHOD_TRANSFER = "transfer";
    public const PAYMENT_METHOD_CASH = "cash";
    public const PAYMENT_METHOD_CREDIT_CARD = "credit_card";
    public const PAYMENT_METHOD_OTHER = "other";

    protected $fillable = [
        "reference",
        "total",
        "payment_method",
        "bank",
        "other_method",
        "invoice_id"
    ];

    public static function paymentMethodKeyValue()
    {
        return [
            [
                "value" => self::PAYMENT_METHOD_OTHER,
                "key" => __("payment_method." . self::PAYMENT_METHOD_OTHER),
            ],
            [
                "value" => self::PAYMENT_METHOD_CASH,
                "key" => __("payment_method." . self::PAYMENT_METHOD_CASH),
            ],
            [
                "value" => self::PAYMENT_METHOD_CREDIT_CARD,
                "key" => __("payment_method." . self::PAYMENT_METHOD_CREDIT_CARD),
            ],
            [
                "value" => self::PAYMENT_METHOD_TRANSFER,
                "key" => __("payment_method." . self::PAYMENT_METHOD_TRANSFER),
            ],

        ];
    }

    public function registrable()
    {
        return $this->morphTo();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function evidence()
    {
        return $this->morphOne(Image::class, "imageable");
    }
}
