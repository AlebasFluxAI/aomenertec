<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Icon extends Singleton
{
    public static function getIcon()
    {
        try {
            return self::getUserIcon();
        } catch (Throwable $exception) {
            return "https://aom.enerteclatam.com/images/logo-horizontal.svg";
        }
    }

    private static function getUserIcon()
    {
        if ($admin = Auth::user()->getAdmin()) {
            return $admin->icon->url;
        }
        return "https://aom.enerteclatam.com/images/logo-horizontal.svg";
    }
}
