<?php

namespace App\Observers\OtpUser;

use App\Models\V1\OtpUser;
use Illuminate\Support\Str;

class OtpUserObserver
{
    public function creating(OtpUser $otpUser)
    {
        OtpUser::whereUserId($otpUser->user_id)->update([
            "enabled" => false
        ]);
        $otpUser->otp = Str::uuid()->toString();
        $otpUser->enabled = true;
    }
}
