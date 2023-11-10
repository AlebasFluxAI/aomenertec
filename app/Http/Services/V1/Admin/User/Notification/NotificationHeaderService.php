<?php

namespace App\Http\Services\V1\Admin\User\Notification;

use App\Http\Services\Singleton;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationHeaderService extends Singleton
{
    public function mount(Component $component)
    {
        $component->user = Auth::user();
        $this->refreshNotificationCounter($component);
    }

    public function refreshNotificationCounter(Component $component)
    {
        $component->notificationCounter = $component->user->unreadNotifications->whereNull("deleted_at")->count();
    }
}
