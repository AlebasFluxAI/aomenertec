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
use Illuminate\Support\Facades\Route;

class Subdomain
{
    public const SUBDOMAIN_COENERGIA = "coenergia";
    public const SUBDOMAIN_AOM = "aom";

    public static function getTitle()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "Enertec",
            self::SUBDOMAIN_COENERGIA => "Coenergia",
            default => "Enertec",
        };
    }

    public static function getIcon()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "https://enerteclatam.com/media/wkvhaio3/favicon.png",
            self::SUBDOMAIN_COENERGIA => "https://enertedevops.s3.us-east-2.amazonaws.com/images/coenergia-icon.jpeg",
            default => "https://enerteclatam.com/media/wkvhaio3/favicon.png",
        };
    }

    public static function getHeaderIcon()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "https://aom.enerteclatam.com/images/logo-horizontal.svg",
            self::SUBDOMAIN_COENERGIA => "https://enertedevops.s3.us-east-2.amazonaws.com/images/16517642985208516/1651764298_Coenergia_login.png",
            default => "https://aom.enerteclatam.com/images/logo-horizontal.svg",
        };
    }

    public static function getHeaderColor()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "background-color:black;border-bottom-color:#009599;border-bottom-width:0.2rem",
            self::SUBDOMAIN_COENERGIA => "background-color:whitesmoke;border-bottom-color:#3962a8;border-bottom-width:0.2rem",
            default => "background-color:black;border-bottom-color:#009599;border-bottom-width:0.2rem",
        };
    }


}
