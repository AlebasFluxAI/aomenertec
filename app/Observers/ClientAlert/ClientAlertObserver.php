<?php

namespace App\Observers\ClientAlert;

use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\EquipmentType;
use App\Models\V1\User;
use App\Notifications\Alert\AlertNotification;
use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class ClientAlertObserver
{
    /**
     * Handle the ClientAlert "created" event.
     *
     * @param  \App\Models\V1\ClientAlert  $clientAlert
     * @return void
     */
    public function created(ClientAlert $clientAlert)
    {
        $client =$clientAlert->client;
        $technicians = $client->clientTechnician;
        $supervisors = $client->supervisors;
        $flag = true;
        if ($clientAlert->type == ClientAlert::ALERT){
            foreach ($technicians as $user) {
                event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                $user->user->notify(new AlertNotification($clientAlert));
            }
            foreach ($supervisors as $user) {
                if ($user->user->phone == $client->phone) {
                    $flag = false;
                }
                event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                $user->user->notify(new AlertNotification($clientAlert));
            }
            if ($flag) {
                $client->notify(new AlertNotification($clientAlert));
            }
        }
        /*if ($clientAlert->type == ClientAlert::CONTROL){
            $client_alert_configuration = $clientAlert->clientAlertConfiguration;
            if ($client_alert_configuration->active_control){
                $digital_output = $client_alert_configuration->clientDigitalOutput()->get();
                $equipment = $client->equipments()->whereEquipmentTypeId(1)->first();
                $topic = "mc/config/" . $equipment->serial;
                try {
                    $mqtt=MQTT::connection();
                    $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) use ($client, $clientAlert) {
                        if ($elapsedTime >= 50) {
                            $technicians = $client->clientTechnician;
                            $supervisors = $client->supervisors;
                            foreach ($technicians as $user) {
                                event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                $user->user->notify(new AlertNotification($clientAlert));
                            }
                            foreach ($supervisors as $user) {
                                if ($user->user->phone == $client->phone) {
                                    $flag = false;
                                }
                                event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                $user->user->notify(new AlertNotification($clientAlert));
                            }
                            if ($flag) {
                                $client->notify(new AlertNotification($clientAlert));
                            }
                            $mqtt->interrupt();
                        }
                    });
                    foreach ($digital_output as $output){
                        if ($output->status) {
                            $message = "{\"coil" . $output->number . "\":false}";
                        } else {
                            $message = "{\"coil" . $output->number . "\":true}";
                        }
                        $mqtt->publish($topic, $message);
                    }
                    $mqtt->subscribe('mc/ack', function (string $topic, string $message) use ($client, $mqtt, $digital_output, $clientAlert) {
                        $json = json_decode($message, true);
                        if (array_key_exists('coil_ack', $json)) {
                            $equipment_serial = str_pad($json['did'], 6, "0", STR_PAD_LEFT);
                            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                                ->first();
                            if ($equipment) {
                                $client_aux = $equipment->clients()->first();
                                if ($client_aux->id == $client->id) {
                                    if ($json['coil_ack']) {
                                        foreach ($digital_output as $output){
                                            $output->status = !$output->status;
                                            $output->save();
                                        }
                                        $technicians = $client->clientTechnician;
                                        $supervisors = $client->supervisors;
                                        foreach ($technicians as $user) {
                                            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                            $user->user->notify(new AlertNotification($clientAlert));
                                        }
                                        foreach ($supervisors as $user) {
                                            if ($user->user->phone == $client->phone) {
                                                $flag = false;
                                            }
                                            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                            $user->user->notify(new AlertNotification($clientAlert));
                                        }
                                        if ($flag) {
                                            $client->notify(new AlertNotification($clientAlert));
                                        }
                                        $mqtt->interrupt();
                                    }
                                }
                            }
                        }
                    }, 1);
                    $mqtt->loop(true);
                    $mqtt->disconnect();
                } catch (MqttClientException $e) {

                }

            }
        }*/
    }

    /**
     * Handle the ClientAlert "updated" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function updated(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "deleted" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function deleted(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "restored" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function restored(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "force deleted" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function forceDeleted(ClientAlert $clientAlert)
    {
        //
    }
}
