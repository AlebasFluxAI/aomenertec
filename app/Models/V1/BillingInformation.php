<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingInformation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const BILLING_TYPE_PREPAID = "prepaid";
    public const BILLING_TYPE_POSTPAID = "postpaid";
    public const BILLING_TYPE_NONE = "none";
    protected $table = "billing_informations";
    protected $fillable = [
        "client_id",
        "name",
        "address",
        "identification",
        "phone",
        "identification_type",
        "default",
        "type"
    ];

    public static function getBillingType()
    {
        return [
            [
                "key" => "Prepago",
                "value" => BillingInformation::BILLING_TYPE_PREPAID
            ],
            [
                "key" => "Postpago",
                "value" => BillingInformation::BILLING_TYPE_POSTPAID
            ],
            [
                "key" => "Sin facturación",
                "value" => BillingInformation::BILLING_TYPE_NONE
            ],
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
