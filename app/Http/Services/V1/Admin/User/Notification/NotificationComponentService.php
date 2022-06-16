<?php

namespace App\Http\Services\V1\Admin\User\Notification;

use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationComponentService extends Singleton
{
    public function mount(Component $component)
    {
        $component->user = Auth::user();
    }

    public function notificationColor(Component $component, $notification)
    {
        return $notification->read_at ? "white" : "#f2f2f2";
    }

    public function isRead(Component $component, $notification)
    {
        return ($notification->read_at != null);
    }

    public function getData(Component $component)
    {
        return $component->user->notifications()->whereNull("deleted_at")->paginate(15);
    }

    public function markAsRead(Component $component, $model)
    {
        $component->user->notifications()->find($model)->markAsRead();
        event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_READ, $component->user->id));
        $component->mount();
    }

    public function deleteNotification(Component $component, $model)
    {
        $component->user->notifications()->find($model)->update([
            "deleted_at" => now()
        ]);
        event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_DELETED, $component->user->id));
        $component->mount();

    }

}
