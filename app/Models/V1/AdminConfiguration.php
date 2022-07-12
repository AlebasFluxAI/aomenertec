<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminConfiguration extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const FRAME_TYPE_ACTIVE_ENERGY = "active_energy";
    public const FRAME_TYPE_ACTIVE_REACTIVE_ENERGY = "active_reactive_energy";
    public const FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES = "active_reactive_energy_variales";

    protected $fillable = [
        'admin_id',
        'min_value',
        'min_clients',
        'coin'
    ];

    public const COP = 'cop';
    public const USD = 'usd';
}
