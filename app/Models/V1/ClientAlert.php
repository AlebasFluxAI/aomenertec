<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAlert extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;

    public const ALERT = "alert";
    public const CONTROL = "control";

    protected $fillable = [
        'client_id',
        'microcontroller_data_id',
        'client_alert_configuration_id',
        'value',
        'type'
    ];

    public function microcontrollerData()
    {
        return $this->belongsTo(MicrocontrollerData::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientAlertConfiguration()
    {
        return $this->belongsTo(ClientAlertConfiguration::class);
    }
}
