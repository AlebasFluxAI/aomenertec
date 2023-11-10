<?php

namespace App\Http\Services\V1\Admin\User\Notification;

use App\Http\Resources\V1\NotificationTypes;
use App\Http\Services\Singleton;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationComponentService extends Singleton
{
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
        return $component->user->notifications()->whereNull("deleted_at")->paginate();
    }

    public function markAsRead(Component $component, $model)
    {
        $component->user->notifications()->find($model)->markAsRead();
        $component->emit(NotificationTypes::NOTIFICATION_READ, ["notifiable" => $component->user->id]);
        $component->mount();
    }

    public function mount(Component $component)
    {
        $component->user = Auth::user();

    }

    public function deleteNotification(Component $component, $model)
    {
        $component->user->notifications()->find($model)->update([
            "deleted_at" => now()
        ]);
        $component->emit(NotificationTypes::NOTIFICATION_DELETED, ["notifiable" => $component->user->id]);
        $component->mount();
    }
}
