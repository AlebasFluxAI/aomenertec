<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentType;
use App\Notifications\Alert\AlertControlNotification;
use PhpMqtt\Client\MqttClient;

class AlertControlApiStrategy implements MqttSenderInterface
{
    use MqttSenderTrait;

    public const EVENT = "coil_ack";
    private $index;


    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt)
    {
        if ($elapsedTime >= 20) {
            $technicians = $this->client->clientTechnician;
            $supervisors = $this->client->supervisors;
            $flag = true;
            foreach ($technicians as $user) {
                //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                $user->user->notify(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
            }
            foreach ($supervisors as $user) {
                if ($user->user->phone == $this->client->phone) {
                    $flag = false;
                }
                //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                $user->user->notify(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
            }
            if ($flag) {
                $this->client->notify(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
            }
            $mqtt->interrupt();
        }
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function subscribeContext($message, $equipment, $notificationTypeId)
    {
        $webhookEvents = config('data-frame.webhook_events');
        $webhookResponse = json_decode($message, true);
        foreach ($webhookEvents as $event){
            if ($event['notification_type_id'] == $notificationTypeId){
                $json = $event['json'];
                foreach ($json as $item){
                    if ($item['variable_name'] == 'notification_type_id') {
                        if ($webhookResponse['notification_type_id'] == $item['value']) {
                            if ($webhookResponse['success'] == 1) {
                                if ($equipment->serial == $webhookResponse['serial']) {
                                    if ($notificationTypeId == 3) {
                                        echo $notificationTypeId."\n";
                                        dd($webhookResponse['data']);
                                        $data = json_decode($webhookResponse['data'], true);
                                        echo $data['status_coil']."\n";
                                       // dd($this->digital_output);

//                                        foreach ($this->digital_output as $output) {
//                                            $output->status = $data['status_coil'] == 1;
//                                            $output->save();
//                                            break;
//                                        }
//                                        echo $this->digital_output->id."\n";
//                                        $technicians = $this->client->clientTechnician;
//                                        $supervisors = $this->client->supervisors;
//                                        foreach ($technicians as $user) {
//                                            //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
//                                            $user->user->notify(new AlertControlNotification($this->clientAlert, 'control_alert_ok'));
//                                        }
//                                        $flag = true;
//                                        foreach ($supervisors as $user) {
//                                            if ($user->user->phone == $this->client->phone) {
//                                                $flag = false;
//                                            }
//                                            //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
//                                            $user->user->notify(new AlertControlNotification($this->clientAlert, 'control_alert_ok'));
//                                        }
//                                        if ($flag) {
//                                            $this->client->notify(new AlertControlNotification($this->clientAlert, 'control_alert_ok'));
//                                        }
                                        $this->mqtt->interrupt();

                                    }
                                }
                            } else {
                                if ($notificationTypeId == 3) {
                                    $technicians = $this->client->clientTechnician;
                                    $supervisors = $this->client->supervisors;
                                    $flag = true;
                                    foreach ($technicians as $user) {
                                        //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                        $user->user->notify(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
                                    }
                                    foreach ($supervisors as $user) {
                                        if ($user->user->phone == $this->client->phone) {
                                            $flag = false;
                                        }
                                        //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                        $user->user->notify(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
                                    }
                                    if ($flag) {
                                        $this->client->notify(new AlertControlNotification($this->clientAlert, 'alert_control_warning'));
                                    }
                                    $this->mqtt->interrupt();

                                }
                            }
                        }
                        break;
                    }
                }
            }
        }
    }
}
