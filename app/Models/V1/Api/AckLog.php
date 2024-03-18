<?php

namespace App\Models\V1\Api;

use App\Models\V1\Api\EventLog;
use Illuminate\Database\Eloquent\Model;

class AckLog extends Model
{

    public const STATUS_PENDING = "pending";
    public const STATUS_SUCCESS = "success";
    public const STATUS_EXPIRED = "expired";
    public const ACK_LOG_HEADER = "ack_log";
    public const ACK_EVENT_HEADER = "ack_log";
    protected $fillable = [
        "status",
        "serial",
    ];

    public function eventLogs()
    {
        return $this->hasMany(EventLog::class);
    }
}
