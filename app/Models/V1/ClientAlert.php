<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\V1\Api\EventLog;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientAlert extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;
    use PaginatorTrait;


    public const ALERT = "alert";
    public const CONTROL = "control";
    public const INFORMATIVE = "informative";

    protected $fillable = [
        'client_id',
        'microcontroller_data_id',
        'client_alert_configuration_id',
        'value',
        'type',
        'source_timestamp',
        'event_log_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }


    public function microcontrollerData()
    {
        return $this->belongsTo(MicrocontrollerData::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function eventLog()
    {
        return $this->belongsTo(EventLog::class);
    }

    public function clientAlertConfiguration()
    {
        return $this->belongsTo(ClientAlertConfiguration::class);
    }
}
