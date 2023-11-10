<?php

namespace App\Http\Services\V1\Admin\User\ResetPassword;

use App\Http\Services\Singleton;
use App\Models\V1\OtpUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ResetPasswordResetService extends Singleton
{
    public function mount(Component $component, $otp)
    {
        $otp = OtpUser::whereOtp($otp)->first();
        if (!$otp || !$otp->user || !$otp || !$otp->enabled) {
            $component->has_error = true;
            return;
        }
        $component->user = $otp->user;
    }

    public function validatePassword(Component $component)
    {
        if ($component->password_reply != $component->password) {
            $component->addError("password_error", "Las contraseñas no coinciden");
            return;
        }
        if (strlen($component->password) < 8) {
            $component->addError("password_error", "La contraseña debe tener al menos 8 caracteres");
            return;
        }
    }

    public function submitForm(Component $component)
    {
        if ($component->password_reply != $component->password) {
            $component->addError("password_error", "Las contraseñas no coinciden");
            return;
        }
        if (strlen($component->password) < 8) {
            $component->addError("password_error", "La contraseña debe tener al menos 8 caracteres");
            return;
        }

        $component->user->update([
            "password" => Hash::make($component->password)
        ]);
        $component->user->otpUsers()->update([
            "enabled" => false
        ]);

        $component->redirect(route("login"));
    }
}
