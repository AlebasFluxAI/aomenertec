<?php

namespace App\Http\Livewire\V1\Admin\User\Notification;

use App\Http\Services\V1\Admin\User\Notification\NotificationHeaderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationHeader extends Component
{
    public $user;
    public $notifications;
    public $notificationCounter;
    public $user_id;
    protected $listeners =
        [
            "echo:notifications,.notification_created" => 'notifyInputNotification',
            "notification_deleted" => 'notifyDeleteNotification',
            "notification_read" => 'notifyReadNotification'];

    private $notificationHeaderService;

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->notificationHeaderService = NotificationHeaderService::getInstance();
    }

    public function notifyDeleteNotification($notification)
    {
        if ($notification["notifiable"] != $this->user_id) {
            return;
        }
        $this->emitTo(
            'livewire-toast',
            'show',
            ['type' => 'info', 'message' => 'Notificación archivada',
                "position" => "top-left",
                "duration" => "3000",
                'transition_type' => 'zoom_in']
        );

        $this->notificationHeaderService->refreshNotificationCounter($this);
    }

    public function notifyReadNotification($notification)
    {
        if ($notification["notifiable"] != $this->user_id) {
            return;
        }
        $this->emitTo(
            'livewire-toast',
            'show',
            ['type' => 'info', 'message' => 'Notificación marcada como leida',
                "position" => "top-left",
                "duration" => "3000",
                'transition_type' => 'zoom_in']
        );

        $this->notificationHeaderService->refreshNotificationCounter($this);
    }

    public function notifyInputNotification($notification)
    {
        if ($notification["notifiable"] != $this->user_id) {
            return;
        }
        $this->notificationHeaderService->refreshNotificationCounter($this);
        $this->emitTo(
            'livewire-toast',
            'show',
            ['type' => 'info', 'message' => 'Tienes una nueva notificación',
                "position" => "top-right",
                "duration" => "3000",
                'transition_type' => 'zoom_in']
        );
    }


    public function mount()
    {
        $this->user_id = Auth::user()->id;
        $this->notificationHeaderService->mount($this);
    }

    public function redirectNotification()
    {
        $this->redirectRoute("administrar.v1.notificaciones");
    }

    public function render()
    {
        return view('layouts.menu.v1.notifications')
            ->extends('layouts.v1.app');
    }
}
