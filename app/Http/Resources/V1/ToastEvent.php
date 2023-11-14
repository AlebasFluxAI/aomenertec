<?php

namespace App\Http\Resources\V1;

use Livewire\Component;

class ToastEvent
{
    public static function launchToast(Component $component, $event = "show", $type = "success", $message = "", $extra_params = [])
    {
        $component->emitTo('livewire-toast', $event, array_merge(["type" => $type, "message" => $message], $extra_params));
    }
}
