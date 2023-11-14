<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Icon extends Singleton
{
    public static function getIcon()
    {
        try {
            return self::getUserIcon();
        } catch (Throwable $exception) {
            return "https://enertedevops.s3.us-east-2.amazonaws.com/images/enertec-logotipo-new.png";
        }
    }

    private static function getUserIcon()
    {
        if ($admin = Auth::user()->getAdmin()) {
            return $admin->icon->url;
        }
        return "https://enertedevops.s3.us-east-2.amazonaws.com/images/logotipo-enerteclatam.png";
    }

    public static function getUserIconUser($user)
    {
        try {
            if ($admin = $user->getAdmin()) {
                return $admin->icon->url;
            }
        } catch (Throwable $e) {
            return "https://enertedevops.s3.us-east-2.amazonaws.com/images/logotipo-enerteclatam.png";
        }
    }
}
