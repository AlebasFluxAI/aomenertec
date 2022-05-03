<?php

namespace App\Http\Controllers\V1;

use App\Mail\User\UserCratedMail;
use App\Models\V1\User;
use App\Notifications\User\UserCreatedNotification;
use Illuminate\Notifications\Messages\MailMessage;

class MailTestController
{
    public function userCreatedNotification()
    {
        $user = User::whereEmail("herrera_wilder@hotmail.com")->first();
        $user->notify(new UserCreatedNotification());
    }
}
