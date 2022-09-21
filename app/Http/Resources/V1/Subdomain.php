<?php

namespace App\Http\Resources\V1;

use Illuminate\Support\Facades\Route;

class Subdomain
{
    public const SUBDOMAIN_COENERGIA = "coenergia";
    public const SUBDOMAIN_VAUPES = "vaupes";
    public const SUBDOMAIN_AOM = "aom";
    public const SUBDOMAIN_EFENA = "efena";
    public const SUBDOMAIN_DEFAULT = "aom";

    public static function getTitle()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "Enertec",
            self::SUBDOMAIN_COENERGIA => "Coenergia",
            self::SUBDOMAIN_VAUPES => "Gobernación del Vaupes",
            self::SUBDOMAIN_EFENA => "Efena",
            default => "Enertec",
        };
    }

    public static function getIcon() // Esta es la imagen que aparece como favicon en la opagina
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "https://enerteclatam.com/media/wkvhaio3/favicon.png",
            self::SUBDOMAIN_COENERGIA => "https://enertedevops.s3.us-east-2.amazonaws.com/images/coenergia-icon.jpeg",
            self::SUBDOMAIN_VAUPES => "https://enertedevops.s3.us-east-2.amazonaws.com/images/vaupes_icon_login.png",
            self::SUBDOMAIN_EFENA => "https://enertedevops.s3.us-east-2.amazonaws.com/images/logo-efena-icon.png",
            default => "https://enerteclatam.com/media/wkvhaio3/favicon.png",
        };
    }

    public static function getHeaderIcon() // Esta es la imagen que aparece en el header principal
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "https://enertedevops.s3.us-east-2.amazonaws.com/images/enertec-logotipo-new.png",
            self::SUBDOMAIN_COENERGIA => "https://enertedevops.s3.us-east-2.amazonaws.com/images/16517642985208516/1651764298_Coenergia_login.png",
            self::SUBDOMAIN_VAUPES => "https://enertedevops.s3.us-east-2.amazonaws.com/images/VAUPES-1.png",
            self::SUBDOMAIN_EFENA => "https://enertedevops.s3.us-east-2.amazonaws.com/images/logo-efena.png",
            default => "https://enertedevops.s3.us-east-2.amazonaws.com/images/enertec-logotipo-new.png",
        };
    }

    public static function getHeaderColor()
    {
        return match (Route::input("subdomain")) {
            self::SUBDOMAIN_AOM => "justify-content: space-between;padding: 0px;background-color:black;border-bottom-color:#009599;border-bottom-width:0.2rem",
            self::SUBDOMAIN_COENERGIA => "justify-content: space-between;padding: 0px;background-color:whitesmoke;border-bottom-color:#3962a8;border-bottom-width:0.2rem",
            self::SUBDOMAIN_VAUPES => "justify-content: space-between;padding: 0px;background-color:whitesmoke;border-bottom-color:#2a2a75;border-bottom-width:0.2rem",
            self::SUBDOMAIN_EFENA => "justify-content: space-between;padding: 0px;background-color:whitesmoke;border-bottom-color:#3962a8;border-bottom-width:0.2rem",
            default => "background-color:black;border-bottom-color:#009599;border-bottom-width:0.2rem",
        };
    }
}
