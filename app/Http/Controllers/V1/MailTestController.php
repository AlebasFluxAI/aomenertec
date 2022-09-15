<?php

namespace App\Http\Controllers\V1;

use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Mail\User\UserCratedMail;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\User;
use App\Notifications\Alert\AlertNotification;
use App\Notifications\User\UserCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class MailTestController
{
    public function userCreatedNotification(Request $request)
    {
        $user = User::find(2);
        $user->notify(new AlertNotification());
    }

    public function whatsappNotification()
    {
        $clientAlert = ClientAlert::find(3);
        $client =Client::find($clientAlert->client_id);
        $technicians = $client->clientTechnician;
        $supervisors = $client->supervisors;
        $flag = true;
        $network_operator = null;
        foreach ($technicians as $user){
            $network_operator = $user->networkOperator->user;
            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
            $user->user->notify(new AlertNotification($clientAlert));
        }
        foreach ($supervisors as $user){
            if ($user->user->phone == $client->phone){
                $flag = false;
            }
            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
            $user->user->notify(new AlertNotification($clientAlert));
        }
        if ($flag){
            $client->notify(new AlertNotification($clientAlert));
        }
        if ($network_operator){
            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $network_operator->id));
        }
    }
}
