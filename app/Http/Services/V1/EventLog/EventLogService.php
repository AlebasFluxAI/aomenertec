<?php

namespace App\Http\Services\V1\EventLog;

use App\Http\Resources\Json\V1\EventLog\EventLogResource;
use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Request;

class EventLogService
{
    public function getEventLogs(): ResourceCollection
    {
        $response = EventLogResource::collection(EventLog::pagination());
        $eventLog = EventLog::find($this->getEventLogId());
        $eventLog->update([
            "status" => EventLog::STATUS_SUCCESSFUL,
            "response_json" => json_encode($response)
        ]);
        $eventLog->ackLog->update([
            "status" => AckLog::STATUS_SUCCESS
        ]);
        return $response;
    }

    public function getEventLogById(EventLog $eventLog): EventLogResource
    {
        $response = new EventLogResource($eventLog);
        $eventLog = EventLog::find($this->getEventLogId());
        $eventLog->update([
            "status" => EventLog::STATUS_SUCCESSFUL,
            "response_json" => json_encode($response)
        ]);
        $eventLog->ackLog->update([
            "status" => AckLog::STATUS_SUCCESS
        ]);
        return $response;
    }

    public function getEventLogByAckLog(AckLog $ackLog): ResourceCollection
    {
        $response = EventLogResource::collection($ackLog->eventLogs);
        $eventLog = EventLog::find($this->getEventLogId());
        $eventLog->update([
            "status" => EventLog::STATUS_SUCCESSFUL,
            "response_json" => json_encode($response)
        ]);
        $eventLog->ackLog->update([
            "status" => AckLog::STATUS_SUCCESS
        ]);
        return $response;
    }
    public function getEventLogId()
    {
        return json_decode(Request::header(EventLog::EVENT_LOG_HEADER), true)["id"];
    }
}
