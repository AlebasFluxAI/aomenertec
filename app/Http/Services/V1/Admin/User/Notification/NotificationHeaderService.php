<?php

namespace App\Http\Services\V1\Admin\User\Notification;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
