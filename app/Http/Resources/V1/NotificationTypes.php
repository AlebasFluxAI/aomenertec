<?php

namespace App\Http\Resources\V1;

use App\Events\UserNotificationEvent;
use ArrayAccess;

class NotificationTypes
{
    public const NOTIFICATION_CREATED = "notification_created";
    public const NOTIFICATION_READ = "notification_read";
    public const NOTIFICATION_DELETED = "notification_deleted";
}
