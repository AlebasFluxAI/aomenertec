<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabPermission extends Model
{
    use HasFactory;

    public const CLIENT_CONFIG_CONNECTION = "client_config_connection";

    protected $fillable = [
        "permission",
    ];


}
