<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ToastEvent
{
    static public function launchToast(Component $component, $event = "show", $type = "success", $message = "", $extra_params = [])
    {
        $component->emitTo('livewire-toast', $event, array_merge(["type" => $type, "message" => $message], $extra_params));
    }
}
