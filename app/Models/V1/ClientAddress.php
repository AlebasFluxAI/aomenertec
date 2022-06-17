<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class ClientAddress extends Model
{
    use HasFactory;


    public const STATUS_ENABLED = "enabled";
    public const STATUS_DISABLED = "disabled";

    protected $fillable = [
        "latitude",
        "longitude",
        "address",
        "country",
        "city",
        "state",
        "client_id",
        "here_maps",
        "postal_code",
        "status",
        "details"
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
