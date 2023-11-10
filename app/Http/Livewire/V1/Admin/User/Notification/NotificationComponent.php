<?php

namespace App\Http\Livewire\V1\Admin\User\Notification;

use App\Http\Services\V1\Admin\User\Notification\NotificationComponentService;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationComponent extends Component
{
    use WithPagination;

    public $user;
    private $notificationComponentService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->notificationComponentService = NotificationComponentService::getInstance();
    }

    public function notificationColor($notification)
    {
        return $this->notificationComponentService->notificationColor($this, $notification);
    }

    public function mount()
    {
        $this->notificationComponentService->mount($this);
    }

    public function isRead($notification)
    {
        return $this->notificationComponentService->isRead($this, $notification);
    }

    public function markAsRead($notification)
    {
        $this->notificationComponentService->markAsRead($this, $notification);
    }

    public function deleteNotification($notification)
    {
        $this->notificationComponentService->deleteNotification($this, $notification);
    }


    public function render()
    {
        return view(
            'livewire.v1.admin.notification.notifications',
            [
                "data" => $this->getData(),
            ]
        )
            ->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->notificationComponentService->getData($this);
    }
}
