<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\Api\ApiKey;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentType;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use App\Strategy\MqttSenderPattern\AlertControlApiStrategy;
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
    public $client;
    public $digital_output;

    public function __construct($clientAlert)
    {
        $this->clientAlert = $clientAlert->withoutRelations();
        $this->client = $this->clientAlert->client;
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
            echo "control\n";
            $client_alert_configuration = $this->clientAlert->clientAlertConfiguration;
            if ($client_alert_configuration->active_control) {
                $digital_output = $client_alert_configuration->clientDigitalOutput()->get();
                $this->digital_output = $digital_output;
                $equipment= $this->client->equipments()->whereEquipmentTypeId(7)->first();
                $apiKey =ApiKey::first();

                try {
                    $mqtt = \App\ModulesAux\MQTT::connection('default', 'null');
                    $mqttCoilAckStrategy = new AlertControlApiStrategy($mqtt, $this);
                    foreach ($digital_output as $output) {
                        if ($output->pivot->control_status == ClientDigitalOutputAlertConfiguration::CHANGE) {
                            if ($output->status) {
                                $value = false;
                            } else {
                                $value = true;
                            }
                        } elseif ($output->pivot->control_status == ClientDigitalOutputAlertConfiguration::ON) {
                            $value = true;
                        } else {
                            $value = false;
                        }
                        $requestDetails = [
                            'url' => 'https://aom.enerteclatam.com/api/v1/config/set-status-coil',
                            'method' => 'GET',
                            'body' => [
                                'serial' => $equipment->serial,
                                'status' => $value
                            ],
                            'apiKey' => $apiKey->api_key
                        ];
                        $mqttCoilAckStrategy->fetchDataFromAPI($requestDetails);
                        break;
                    }
                    $mqttCoilAckStrategy->registerLoopEventHandler();
                    $mqttCoilAckStrategy->subscribe($equipment, 3);

                } catch (MqttClientException $e) {

                }
            }
        }
    }
}
