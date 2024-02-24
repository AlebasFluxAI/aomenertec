<?php

namespace App\Models\V1\Api;

use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class EventLog extends Model
{
    use FilterTrait;

    const EVENT_LOG_HEADER = "event_log_header";
    const EVENT_LOG_HEADER_ID = "event_log_header_id";
    const API_EVENT_HEADER = "api_event_header";

    const CLIENT_MAIN_SERVER_REQUEST = "client_main_server_request";
    const MAIN_SERVER_MC_REQUEST = "main_server_mc_request";
    const MAIN_SERVER_CLIENT_RESPONSE = "main_server_client_response";

    const EVENT_DATE_RANGE = "date-range";
    const EVENT_SET_STATUS_COIL = "set-status-coil";
    const EVENT_GET_STATUS_COIL = "get-status-coil";
    const EVENT_SET_DATE = "set-date";
    const EVENT_GET_DATE = "get-date";
    const EVENT_GET_CONFIG_SENSOR = "get-config-sensor";
    const EVENT_SET_CONFIG_SENSOR = "set-config-sensor";
    const EVENT_GET_STATUS_SENSOR = "get-status-sensor";
    const EVENT_GET_EVENT_LOGS = "event_logs";


    const EVENT_SET_STATUS_SENSOR = "set-status-sensor";
    const EVENT_GET_STATUS_METER = "get-status-meter";
    const EVENT_SET_URL_NOTIFICATION = "set-url-notification";
    const EVENT_GET_URL_NOTIFICATION = "get-url-notification";
    const EVENT_SET_TIMESTAMP = "set-timestamp";
    const EVENT_GET_TIMESTAMP = "get-timestamp";
    const EVENT_ADD_CLIENT = "client-add";


    const STATUS_CREATED = "created";
    const STATUS_ERROR = "error";
    const STATUS_SUCCESSFUL = "successful";

    protected $fillable = [
        "name",
        "event",
        "client_id",
        "request_endpoint",
        "request_json",
        "request_type",
        "response_json",
        "webhook",
        "status",
        "ack_log_id"
    ];

    public static function getEvents($uri)
    {
        foreach ([self::EVENT_DATE_RANGE,
                     self::EVENT_SET_STATUS_COIL,
                     self::EVENT_GET_STATUS_COIL,
                     self::EVENT_SET_DATE,
                     self::EVENT_GET_DATE,
                     self::EVENT_GET_CONFIG_SENSOR,
                     self::EVENT_SET_CONFIG_SENSOR,
                     self::EVENT_GET_STATUS_SENSOR,
                     self::EVENT_GET_STATUS_METER,
                     self::EVENT_SET_URL_NOTIFICATION,
                     self::EVENT_GET_URL_NOTIFICATION,
                     self::EVENT_ADD_CLIENT,
                     self::EVENT_GET_EVENT_LOGS
                 ] as $event) {
            if (strpos($uri, $event)) {
                return $event;
            }
        }
        return null;
    }

    public static function createEvent($ackLog, $requestJson, $requestType, $responseJson, $webhook)
    {

        $event = Request::header(EventLog::API_EVENT_HEADER);
        return self::create([
            "name" => $event . "_" . $requestType,
            "event" => $event,
            "client_id" => Request::header(Client::CLIENT_HEADER),
            "request_endpoint" => Request::getRequestUri(),
            "request_json" => $requestJson,
            "response_json" => $responseJson,
            "webhook" => $webhook,
            "request_type" => $requestType,
            "status" => self::STATUS_CREATED,
            "serial" => $ackLog->serial,
            "ack_log_id" => $ackLog ? $ackLog->id : null
        ]);
    }

    public static function createMcEvent($ackLog, $request, $requestType, $responseJson, $webhook)
    {

        $event = $request->header(EventLog::API_EVENT_HEADER);
        return self::create([
            "name" => $event . "_" . $requestType,
            "event" => $event,
            "client_id" => $request->header(Client::CLIENT_HEADER),
            "request_endpoint" => null,
            "request_json" => null,
            "response_json" => $responseJson,
            "webhook" => $webhook,
            "request_type" => $requestType,
            "status" => self::STATUS_CREATED,
            "ack_log_id" => $ackLog ? $ackLog->id : null
        ]);
    }

    public function ackLog()
    {
        return $this->belongsTo(AckLog::class);
    }

    public function updateResponse($responseJson)
    {
        $this->update([
            "response_json" => $responseJson,

        ]);
    }
}
