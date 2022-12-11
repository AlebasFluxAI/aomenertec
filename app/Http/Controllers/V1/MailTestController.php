<?php

namespace App\Http\Controllers\V1;

use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Jobs\V1\Enertec\JsonEdit;
use App\Mail\User\UserCratedMail;
use App\Mail\User\UserResetPasswordMail;
use App\Mail\WorkOrder\WorkOrderUpdatedMail;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Notifications\Alert\AlertNotification;
use App\Notifications\User\UserCreatedNotification;
use App\Notifications\User\UserResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class MailTestController
{
    public function userCreatedNotification()
    {

        return (new WorkOrderUpdatedMail(WorkOrder::find(29)))->render();
    }

    public function whatsappNotification()
    {
        $datum = MicrocontrollerData::find(1905135);
        //$datum->jsonEdit(false);
        dispatch(new JsonEdit($datum, false))->onQueue('spot');

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
