<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientConfiguration extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const CONECTION_TYPE_4G = "4g";
    public const CONECTION_TYPE_OTHERS = "others";

    public const FRAME_TYPE_ACTIVE_ENERGY = "active_energy";
    public const FRAME_TYPE_ACTIVE_REACTIVE_ENERGY = "active_reactive_energy";
    public const FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES = "active_reactive_energy_variales";


    public const STORAGE_LATENCY_TYPE_HOURLY = "hourly";
    public const STORAGE_LATENCY_TYPE_DAILY = "daily";
    public const STORAGE_LATENCY_TYPE_MONTHLY = "monthly";

    public const STORAGE_LATENCY_OPTIONS =
        [
            self::STORAGE_LATENCY_TYPE_HOURLY => [
                ["key" => "1/hora", "value" => 1],
                ["key" => "2/hora", "value" => 2],
                ["key" => "3/hora", "value" => 3],
                ["key" => "4/hora", "value" => 4],
                ["key" => "6/hora", "value" => 6],
                ["key" => "12/hora", "value" => 12],
                ["key" => "60/hora", "value" => 60],

            ],
            self::STORAGE_LATENCY_TYPE_DAILY => [
                ["key" => "1/día", "value" => 1],
                ["key" => "2/día", "value" => 2],
                ["key" => "3/día", "value" => 3],
                ["key" => "4/día", "value" => 4],
                ["key" => "8/día", "value" => 8],
                ["key" => "12/día", "value" => 12],
            ],
            self::STORAGE_LATENCY_TYPE_MONTHLY => [
                ["key" => "Día 1", "value" => 1],
                ["key" => "Día 2", "value" => 2],
                ["key" => "Día 3", "value" => 3],
                ["key" => "Día 4", "value" => 4],
                ["key" => "Día 5", "value" => 5],
                ["key" => "Día 6", "value" => 6],
                ["key" => "Día 7", "value" => 7],
                ["key" => "Día 8", "value" => 8],
                ["key" => "Día 9", "value" => 9],
                ["key" => "Día 10", "value" => 10],
                ["key" => "Día 11", "value" => 11],
                ["key" => "Día 12", "value" => 12],
                ["key" => "Día 13", "value" => 13],
                ["key" => "Día 14", "value" => 14],
                ["key" => "Día 15", "value" => 15],
                ["key" => "Día 16", "value" => 16],
                ["key" => "Día 17", "value" => 17],
                ["key" => "Día 18", "value" => 18],
                ["key" => "Día 19", "value" => 19],
                ["key" => "Día 20", "value" => 20],
                ["key" => "Día 21", "value" => 21],
                ["key" => "Día 22", "value" => 22],
                ["key" => "Día 23", "value" => 23],
                ["key" => "Día 24", "value" => 24],
                ["key" => "Día 25", "value" => 25],
                ["key" => "Día 26", "value" => 26],
                ["key" => "Día 27", "value" => 27],
                ["key" => "Día 28", "value" => 28],
                ["key" => "Ultimo dia del mes", "value" => 31]
            ],

        ];

    protected $fillable = [
        'client_id',
        "ssid",
        "wifi_password",
        "mqtt_host",
        "mqtt_port",
        "mqtt_user",
        "mqtt_password",
        "real_time_flag",
        "real_time_latency",
        "storage_latency",
        "storage_type_latency",
        "digital_outputs",
        "active_real_time",
        "connection_type",
        "billing_day",
        "automatic_control"
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
