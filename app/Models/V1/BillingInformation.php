<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingInformation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "billing_informations";

    protected $fillable = [
        "client_id",
        "name",
        "address",
        "identification",
        "phone",
        "identification_type",
        "default"
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
