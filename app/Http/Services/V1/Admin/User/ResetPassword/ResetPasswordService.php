<?php

namespace App\Http\Services\V1\Admin\User\ResetPassword;

use App\Http\Resources\V1\Menu;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Notifications\User\UserResetPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ResetPasswordService extends Singleton
{
    public function resetPassword(Component $component)
    {
        if (!$user = User::whereEmail($component->email)->first()) {
            $component->addError('reset_error', 'Correo electronico no registrado');
        }
        if ($user == null) {
            $component->addError('reset_error', 'Correo electronico no registrado');
        }
        $otp = $user->otpUsers()->create();
        $user->notifyNow(new UserResetPasswordNotification($otp));
        $component->reset();
        ToastEvent::launchToast($component, "show", "success", "Enlace enviado exitosamente");
    }
}
