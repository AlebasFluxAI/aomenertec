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

        if ($admin = Auth::user()->admin) {
            return $admin->icon->url;
        }
        if ($networkOperator = Auth::user()->networkOperator) {
            return $networkOperator->admin->icon->url;
        }

        if ($seller = Auth::user()->seller) {
            return $seller->networkOperator->admin->icon->url;
        }
        if ($supervisor = Auth::user()->supervisor) {
            return $supervisor->networkOperator->admin->icon->url;

        }
        if ($technician = Auth::user()->technician) {
            return $technician->networkOperator->admin->icon->url;

        }
        return "https://aom.enerteclatam.com/images/logo-horizontal.svg";
    }
}
