<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDigitalOutputAlertConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_alert_configuration_id',
        'client_digital_output_id'
    ];
}
