<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDigitalOutputAlertConfiguration extends Model
{
    use HasFactory;
    use PaginatorTrait;


    public const ON = 'on';
    public const OFF = 'off';
    public const CHANGE = 'change';
    public const CONTROL_OPTIONS = [
        ['value' => self::ON, 'key' => 'ON'],
        ['value' => self::OFF, 'key' => 'OFF'],
        ['value' => self::CHANGE, 'key' => 'Cambio'],
    ];
    protected $fillable = [
        'client_alert_configuration_id',
        'client_digital_output_id',
        'control_status'
    ];

}
