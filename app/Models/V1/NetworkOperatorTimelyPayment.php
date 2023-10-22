<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkOperatorTimelyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        "network_operator_id",
        "days_to_disconnection",
        "days_to_payment",
        "reconnection_cost"
    ];

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
}
