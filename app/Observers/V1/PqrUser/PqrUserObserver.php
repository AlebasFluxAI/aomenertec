<?php

namespace App\Observers\V1\PqrUser;

use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Models\V1\PqrUser;
use App\Models\V1\User;
use App\Notifications\Alert\PqrNotification;

class PqrUserObserver
{
    public function created(PqrUser $pqrUser)
    {
        $user_id = $pqrUser->user_id;
        event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user_id));
        $user = User::find($user_id);
        $user->notify(new PqrNotification($pqrUser->pqr));
    }
}
