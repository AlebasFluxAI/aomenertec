<?php

namespace App\Http\Controllers\V1;

use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Mail\User\UserCratedMail;
use App\Models\V1\User;
use App\Notifications\Alert\AlertNotification;
use App\Notifications\User\UserCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class MailTestController
{
    public function userCreatedNotification(Request $request)
    {

        $user = User::find($request->input("user_id"));
        $user->notify(new AlertNotification());
        event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->id));

    }

    public function whatsappNotification()
    {
        $user = User::wherePhone("3209720220")->first();

        $user->notify(new AlertNotification());
    }
}
