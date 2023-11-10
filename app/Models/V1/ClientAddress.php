<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    use HasFactory;
    use AuditableTrait;
    use PaginatorTrait;


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
