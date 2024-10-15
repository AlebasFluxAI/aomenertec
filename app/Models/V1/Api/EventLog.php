<?php

namespace App\Models\V1\Api;

use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\V1\Api\AckLog;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use App\Models\V1\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class EventLog extends Model
{
    use FilterTrait;
    use ImageableTrait;
    use PaginatorTrait;


    const EVENT_LOG_HEADER = "event_log_header";
    const EVENT_LOG_HEADER_ID = "event_log_header_id";
    const API_EVENT_HEADER = "api_event_header";

    const CLIENT_MAIN_SERVER_REQUEST = "client_main_server_request";
    const MAIN_SERVER_MC_REQUEST = "main_server_mc_request";
    const MAIN_SERVER_CLIENT_RESPONSE = "main_server_client_response";


    const EVENT_SET_ALERT_LIMITS = "set-alert-limits";
    const SET_REACTIVE_DATA = "set-reactive-data";
    const EVENT_SET_ALERT_TIME = "set-alert-time";
    const EVENT_SET_SAMPLING_TIME = "set-sampling-time";
    const EVENT_SET_WIFI_CREDENTIALS = "set-wifi-credentials";
    const EVENT_SET_BROKER_CREDENTIALS = "set-broker-credentials";
    const EVENT_SET_DATE = "set-date";
    const EVENT_GET_DATE = "get-date";
    const EVENT_SET_STATUS_COIL = "set-status-coil";
    const EVENT_GET_STATUS_COIL = "get-status-coil";
    const EVENT_SET_CONFIG_SENSOR = "set-config-sensor";
    const EVENT_GET_CONFIG_SENSOR = "get-config-sensor";
    const EVENT_GET_STATUS_SENSOR = "get-status-sensor";
    const EVENT_GET_STATUS_CONNECTION = "get-status-connection";
    const EVENT_GET_CURRENT_READINGS = "get-current-readings";
    const EVENT_ON_OFF_REAL_TIME = "set-status-real-time";
    const EVENT_CHANGE_STATE_SUPPLY_IN_APLICATION = "change-state-supply-in-application";
    const EVENT_CHANGE_STATE_DOOR = "change-state-door";
    const EVENT_SUPPLY_INTERRUPTION_TO_MANIPULATION = "supply-interruption-to-manipulation";
    const EVENT_CHANGE_STATE_SUPPLY_TO_VOLTAGE = "change-state-supply-to-voltage";
    const EVENT_METER_READING_FAILURE = "meter-reading-failure";
    const EVENT_INITIAL_CONNECTION = "initial-connection";
    const EVENT_LOST_CONNECTION = "lost-connection";
    const EVENT_ALERT_NOTIFICATION = "alert-notification";
    const EVENT_ALERT_CONTROL_NOTIFICATION = "alert-control-notification";
    const EVENT_OTA_UPDATE = "ota-update";
    const EVENT_REAL_TIME_FRAME = "real-time-frame";
    const EVENT_SET_CONTROL_LIMITS = "set-control-limits";
    const EVENT_SET_STATUS_CONTROL_LIMITS = "set-status-control-limits";
    const EVENT_SET_BILLING_DAY = "set-billing-day";
    const EVENT_SET_SERVICE_COIL = "set-status-service-coil";
    const EVENT_SET_PASSWORD_METER_APP = "set-password-meter-app";
    const EVENT_GET_PASSWORD_METER = "get-password-meter";
    const EVENT_CHANGE_STATE_SERVICE_COIL_IN_APLICATION = "set-status-service-coil-in-aplication";
    const EVENT_CHANGE_PASSWORD_IN_APLICATION = "set-password-meter-in-aplication";



    const EVENT_GET_EVENT_LOGS = "event_logs";

    const EVENT_DATE_RANGE = "date-range";



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
        "ack_log_id",
        "serial"
    ];

    public static function getEvents($uri)
    {
        foreach ([
                     self::EVENT_SET_ALERT_LIMITS,
                     self::EVENT_SET_ALERT_TIME,
                     self::EVENT_SET_SAMPLING_TIME,
                     self::EVENT_SET_WIFI_CREDENTIALS,
                     self::EVENT_SET_BROKER_CREDENTIALS,
                     self::EVENT_SET_DATE,
                     self::EVENT_GET_DATE,
                     self::EVENT_SET_STATUS_COIL,
                     self::EVENT_GET_STATUS_COIL,
                     self::EVENT_SET_CONFIG_SENSOR,
                     self::EVENT_GET_CONFIG_SENSOR,
                     self::EVENT_GET_STATUS_SENSOR,
                     self::EVENT_GET_STATUS_CONNECTION,
                     self::EVENT_GET_CURRENT_READINGS,
                     self::EVENT_ON_OFF_REAL_TIME,
                     self::EVENT_SET_CONTROL_LIMITS,
                     self::EVENT_SET_STATUS_CONTROL_LIMITS,
                     self::EVENT_OTA_UPDATE,
                     self::EVENT_SET_BILLING_DAY,
                     self::EVENT_SET_SERVICE_COIL,
                     self::EVENT_SET_PASSWORD_METER_APP,
                     self::EVENT_GET_PASSWORD_METER,
                     self::EVENT_CHANGE_STATE_SERVICE_COIL_IN_APLICATION,
                     self::EVENT_CHANGE_PASSWORD_IN_APLICATION,
                     self::EVENT_DATE_RANGE,
                     self::EVENT_SET_URL_NOTIFICATION,
                     self::EVENT_GET_URL_NOTIFICATION,
                     self::EVENT_ADD_CLIENT,
                     self::EVENT_GET_EVENT_LOGS,
                     self::SET_REACTIVE_DATA
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
            "serial" => $ackLog->serial,
            "ack_log_id" => $ackLog ? $ackLog->id : null
        ]);
    }

    public function ackLog()
    {
        return $this->belongsTo(AckLog::class);
    }
    public function evidences()
    {
        return $this->morphMany(Image::class, "imageable")->whereType("evidences");
    }

    public function updateResponse($responseJson)
    {
        $this->update([
            "response_json" => $responseJson,

        ]);
    }
}
