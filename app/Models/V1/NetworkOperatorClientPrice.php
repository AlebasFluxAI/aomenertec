<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkOperatorClientPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        "network_operator_id",
        "client_type_id",
        "value",
    ];

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function clientType()
    {
        return $this->belongsTo(ClientType::class);
    }
}
