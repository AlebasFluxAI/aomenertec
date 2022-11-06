<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabPermission extends Model
{
    use HasFactory;
    use PaginatorTrait;


    public const CLIENT_CONFIG_CONNECTION = "client_config_connection";
    public const CLIENT_MONITORING_REAL_TIME = "client_monitoring_real_time";


    protected $fillable = [
        "permission",
        "details"
    ];
}
