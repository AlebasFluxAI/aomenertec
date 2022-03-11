<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAlert extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        "client_id",
        "alert_type_id",
        "value"
    ];
}
