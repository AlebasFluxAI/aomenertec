<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Style extends Singleton
{
    public static function getStyle()
    {
        try {
            return "assets/css/" . self::getUserStyle() . '.css';
        } catch (Throwable $exception) {
            return "assets/css/style.css";
        }
    }

    private static function getUserStyle()
    {
        if ($admin = Auth::user()->admin) {
            return $admin->css_file;
        }
        if ($networkOperator = Auth::user()->networkOperator) {
            return $networkOperator->admin->css_file;
        }

        if ($seller = Auth::user()->seller) {
            return $seller->networkOperator->admin->css_file;
        }
        if ($supervisor = Auth::user()->supervisor) {
            return $supervisor->networkOperator->admin->css_file;
        }
        if ($technician = Auth::user()->technician) {
            return $technician->networkOperator->admin->css_file;
        }
        return "style";
    }
}
