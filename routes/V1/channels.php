<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// TODO [INC-006]: Canales que deberian migrarse a PrivateChannel en el futuro:
// - 'data-monitoring.{clientId}' → Solo el admin/operador del cliente debería ver sus datos
// - 'data-ota-upload.{firmwareId}' → Solo el admin que inició el OTA
// - 'notifications' → Debería ser por usuario: 'notifications.{userId}'
// Cuando se migren, agregar callbacks de autorizacion aquí.
// Ver: https://laravel.com/docs/8.x/broadcasting#authorizing-channels
Broadcast::channel('channel-name', function () {
    return true;
});
