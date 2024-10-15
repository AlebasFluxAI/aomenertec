<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ConfigurationClient\ConfigurationClientController;
use App\Http\Controllers\V1\ConfigurationClient\ClientController;
use App\Http\Controllers\V1\EventLog\EventLogController;
use App\Http\Controllers\V1\MqttInput\MqttInputController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("/v1/mqtt_input", MqttInputController::class);
Route::post("/v1/mqtt_input/real-time", \App\Http\Controllers\V1\MqttInput\MqttRealTimeInputController::class);


Route::group(['prefix' => 'v1/config'], function ()  {
    Route::controller(ConfigurationClientController::class)->group(function () {
        Route::post("/notification-webhook", "notificationWebhook");
    });
});


Route::group(['middleware' => ['token_api_validation', 'event_queue_validation']], function () {

    Route::group(['prefix' => 'v1/clients'], function () {
        Route::controller(ClientController::class)->group(function () {
            Route::post("/client-add", "addClient");
        });
    });
    Route::group(['prefix' => 'v1/event_logs'], function () {
        Route::controller(EventLogController::class)->group(function () {
            Route::get("", "getEventLogs");
            Route::get("/{eventLog}", "getEventLogById");
            Route::get("/ack_logs/{ackLog}", "getEventLogByAckLog");
        });
    });
    Route::group(['prefix' => 'v1/data'], function () {
        Route::controller(ClientController::class)->group(function () {
            Route::get("/date-range", "getDateRangeSerial");
        });
    });



    Route::group(['prefix' => 'v1/config', ], function ()  {
        Route::controller(ConfigurationClientController::class)->group(function () {

            Route::post("/" . \App\Models\V1\Api\EventLog::EVENT_SET_ALERT_LIMITS, "setAlertLimitsForSerial");
            Route::post("/" . \App\Models\V1\Api\EventLog::EVENT_SET_CONTROL_LIMITS, "setControlLimitsForSerial");
            Route::post("/" . \App\Models\V1\Api\EventLog::EVENT_SET_STATUS_CONTROL_LIMITS, "setControlStatusForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_ALERT_TIME, "setAlertTimeForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_SAMPLING_TIME, "setSamplingTimeForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_WIFI_CREDENTIALS, "setWifiCredentialsForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_BROKER_CREDENTIALS, "setBrokerCredentialsForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_DATE, "setDateForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_GET_DATE, "getDateForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_STATUS_COIL, "setStatusCoilForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_GET_STATUS_COIL, "getStatusCoilForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_CONFIG_SENSOR, "setTypeSensorForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_GET_CONFIG_SENSOR, "getTypeSensorForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_GET_STATUS_SENSOR, "getStatusSensorForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_GET_STATUS_CONNECTION, "getStatusConnectionForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_GET_CURRENT_READINGS, "getCurrentReadingsForSerial");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_ON_OFF_REAL_TIME, "OnOffRealTimeForSerial");
            Route::post("/" . \App\Models\V1\Api\EventLog::EVENT_OTA_UPDATE, "otaUpdate");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_BILLING_DAY, "setBillingDay");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_SERVICE_COIL, "setStatusServiceCoil");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_SET_PASSWORD_METER_APP, "setPasswordMeter");
            Route::get("/" . \App\Models\V1\Api\EventLog::EVENT_GET_PASSWORD_METER, "getPasswordMeter");

        });
    });


});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('job-list', 'joblist');
        Route::post('me', 'me');
        Route::post('orders-update', 'ordersUpdate');
        Route::post('order-create', 'orderCreate');
        Route::get('firmwares', 'firmwares');
        Route::get('firmware/{id}', 'firmware');
        Route::post('firmware-create', 'createFirmware');
    });
});
