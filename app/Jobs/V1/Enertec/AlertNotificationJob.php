<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\ClientAlert;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentType;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class AlertNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $clientAlert;

    public function __construct($clientAlert)
    {
        $this->clientAlert = $clientAlert->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = $this->clientAlert->client;
        $technicians = $client->clientTechnician;
        $supervisors = $client->supervisors;
        $flag = true;
        if ($this->clientAlert->type == ClientAlert::ALERT) {
            foreach ($technicians as $user) {
                //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                $user->user->notify(new AlertNotification($this->clientAlert));
            }
            foreach ($supervisors as $user) {
                if ($user->user->phone == $client->phone) {
                    $flag = false;
                }
                //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                $user->user->notify(new AlertNotification($this->clientAlert));
            }
            if ($flag) {
                $client->notify(new AlertNotification($this->clientAlert));
            }
        }
        if ($this->clientAlert->type == ClientAlert::CONTROL) {
            $client_alert_configuration = $this->clientAlert->clientAlertConfiguration;
            if ($client_alert_configuration->active_control) {
                $digital_output = $client_alert_configuration->clientDigitalOutput()->get();
                $equipment = $client->equipments()->whereEquipmentTypeId(1)->first();
                $topic = "mc/config/" . $equipment->serial;
                try {
                    $mqtt = MQTT::connection();
                    foreach ($digital_output as $output) {
                        if ($output->pivot->control_status == ClientDigitalOutputAlertConfiguration::CHANGE) {
                            if ($output->status) {
                                $message = "{\"coil" . $output->number . "\":false}";
                            } else {
                                $message = "{\"coil" . $output->number . "\":true}";
                            }
                        } elseif ($output->pivot->control_status == ClientDigitalOutputAlertConfiguration::ON) {
                            $message = "{\"coil" . $output->number . "\":true}";
                        } else {
                            $message = "{\"coil" . $output->number . "\":false}";
                        }
                        $mqtt->publish($topic, $message);
                        $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) use ($client) {
                            if ($elapsedTime >= 50) {
                                $technicians = $client->clientTechnician;
                                $supervisors = $client->supervisors;
                                $flag = true;
                                foreach ($technicians as $user) {
                                    //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                    $user->user->notifyNow(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
                                }
                                foreach ($supervisors as $user) {
                                    if ($user->user->phone == $client->phone) {
                                        $flag = false;
                                    }
                                    //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                    $user->user->notifyNow(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
                                }
                                if ($flag) {
                                    $client->notifyNow(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
                                }
                                $mqtt->interrupt();
                            }
                        });
                    }
                    $mqtt->subscribe('mc/ack', function (string $topic, string $message) use ($client, $mqtt, $digital_output) {
                        $json = json_decode($message, true);
                        if (array_key_exists('coil_ack', $json)) {
                            $equipment_serial = str_pad($json['did'], 6, "0", STR_PAD_LEFT);
                            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                                ->first();
                            if ($equipment) {
                                $client_aux = $equipment->clients()->first();
                                if ($client_aux->id == $client->id) {
                                    if ($json['coil_ack']) {
                                        foreach ($digital_output as $output) {
                                            if ($output->pivot->control_status == ClientDigitalOutputAlertConfiguration::CHANGE) {
                                                $output->status = !$output->status;
                                                $output->save();
                                            } elseif ($output->pivot->control_status == ClientDigitalOutputAlertConfiguration::ON) {
                                                $output->status = true;
                                                $output->save();
                                            } else {
                                                $output->status = false;
                                                $output->save();
                                            }
                                        }
                                        $technicians = $client->clientTechnician;
                                        $supervisors = $client->supervisors;
                                        foreach ($technicians as $user) {
                                            //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                            $user->user->notifyNow(new AlertControlNotification($this->clientAlert, 'control_alert_ok'));
                                        }
                                        $flag = true;
                                        foreach ($supervisors as $user) {
                                            if ($user->user->phone == $client->phone) {
                                                $flag = false;
                                            }
                                            //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                            $user->user->notifyNow(new AlertControlNotification($this->clientAlert, 'control_alert_ok'));
                                        }
                                        if ($flag) {
                                            $client->notifyNow(new AlertControlNotification($this->clientAlert, 'control_alert_ok'));
                                        }
                                    }
                                }
                            }
                        }
                        $mqtt->interrupt();
                    }, 1);
                    $mqtt->loop(true);
                    $mqtt->disconnect();
                } catch (MqttClientException $e) {

                }
            }
        }
    }
}
