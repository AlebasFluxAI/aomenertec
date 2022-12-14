<?php

namespace App\Http\Controllers\V1;

use App\Events\RealTimeMonitoringEvent;
use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Jobs\V1\Enertec\JsonEdit;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Mail\User\UserCratedMail;
use App\Mail\User\UserResetPasswordMail;
use App\Mail\WorkOrder\WorkOrderUpdatedMail;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use App\Notifications\User\UserCreatedNotification;
use App\Notifications\User\UserResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class MailTestController
{
    public function userCreatedNotification()
    {

        return (new WorkOrderUpdatedMail(WorkOrder::find(29)))->render();
    }

    public function whatsappNotification()
    {
        //$datum = MicrocontrollerData::find(1905135);
        //$datum->jsonEdit(false);
        //dispatch(new SerializeMicrocontrollerDataJob('2022-12-08 08:05:00'))->onQueue('default');
        //$user = User::find(1);
        //event(new RealTimeMonitoringEvent("hola"));
        //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->id));
        $clientAlert = ClientAlert::find(1369);
        $client =$clientAlert->client;
        $client->notify(new AlertControlNotification($clientAlert));
        dd("ok");
        /*$technicians = $client->clientTechnician;
        $supervisors = $client->supervisors;
        $flag = true;

        if ($clientAlert->type == ClientAlert::CONTROL){
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
                               //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                               $user->user->notify(new AlertControlNotification($clientAlert, 'alert_control_warning'));
                           }
                           foreach ($supervisors as $user) {
                               if ($user->user->phone == $client->phone) {
                                   $flag = false;
                               }
                               //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                               $user->user->notify(new AlertControlNotification($clientAlert, 'alert_control_warning'));
                           }
                           if ($flag) {
                               $client->notify(new AlertControlNotification($clientAlert, 'alert_control_warning'));
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
                                           //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                           $user->user->notify(new AlertControlNotification($clientAlert, 'alert_control_success'));
                                       }
                                       $flag = true;
                                       foreach ($supervisors as $user) {
                                           if ($user->user->phone == $client->phone) {
                                               $flag = false;
                                           }
                                           //event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
                                           $user->user->notify(new AlertControlNotification($clientAlert, 'alert_control_success'));
                                       }
                                       if ($flag) {
                                           $client->notify(new AlertControlNotification($clientAlert, 'alert_control_success'));
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
       }*/
        /*$clientAlert = ClientAlert::find(3);
        $client = Client::find($clientAlert->client_id);
        $technicians = $client->clientTechnician;
        $supervisors = $client->supervisors;
        $flag = true;
        $network_operator = null;
        foreach ($technicians as $user) {
            $network_operator = $user->networkOperator->user;
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
        if ($network_operator) {
            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $network_operator->id));
        }*/
    }
}
