<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ConfigurationClient\ConfigurationClientController;
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


Route::group(['prefix' => 'v1/config', 'namespace' => 'V1\ConfigurationClient'], function ()  {
    Route::post("/notification-webhook", "ConfigurationClientController@notificationWebhook");
});

Route::group(['middleware' => ['token_auth', 'event_queue_validator']], function () {
    Route::group(['prefix' => 'v1/config', 'namespace' => 'V1\ConfigurationClient'], function ()  {
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_ALERT_LIMITS, "ConfigurationClientController@setAlertLimitsForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_ALERT_TIME, "ConfigurationClientController@setAlertTimeForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_SAMPLING_TIME, "ConfigurationClientController@setSamplingTimeForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_WIFI_CREDENTIALS, "ConfigurationClientController@setWifiCredentialsForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_BROKER_CREDENTIALS, "ConfigurationClientController@setBrokerCredentialsForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_DATE, "ConfigurationClientController@setDateForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_GET_DATE, "ConfigurationClientController@getDateForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_STATUS_COIL, "ConfigurationClientController@setStatusCoilForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_GET_STATUS_COIL, "ConfigurationClientController@getStatusCoilForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_SET_CONFIG_SENSOR, "ConfigurationClientController@setTypeSensorForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_GET_CONFIG_SENSOR, "ConfigurationClientController@getTypeSensorForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_GET_STATUS_SENSOR, "ConfigurationClientController@getStatusSensorForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_GET_STATUS_CONNECTION, "ConfigurationClientController@getStatusConnectionForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_GET_CURRENT_READINGS, "ConfigurationClientController@getCurrentReadingsForSerial");
        Route::get("/".\App\Models\V1\Api\EventLog::EVENT_ON_OFF_REAL_TIME, "ConfigurationClientController@OnOffRealTimeForSerial");
        Route::post("/".\App\Models\V1\Api\EventLog::EVENT_OTA_UPDATE, "ConfigurationClientController@otaUpdate");
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
    });
});
