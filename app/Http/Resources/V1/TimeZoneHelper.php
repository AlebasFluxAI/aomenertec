<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;

class TimeZoneHelper extends Singleton
{
    public const COLOMBIA = "America/Bogota";
    public const REPUBLICA_DOMINICANA = "America/Santo_Domingo";
    public const BRASIL_ACRE = "Brazil/Acre";
    public const BRASIL_DENORONHA = "Brazil/DeNoronha";
    public const BRASIL_EAST = "Brazil/East";
    public const BRASIL_WEST = "Brazil/West";
    public const ARGENTINA = "America/Buenos_Aires";
    public const BOLIVIA = "America/La_Paz";
    public const ECUADOR = "America/Guayaquil";
    public const PARAGUAY = "America/Asuncion";
    public const PERU = "America/Lima";
    //public const EEUU = "+1";
    //public const MEXICO = "+52";
    public const NICARAGUA = "	America/Managua";
    public const COSTA_RICA = "America/Costa_Rica";
    public const GUATEMALA = "America/Guatemala";
    public const ESPAÑA = "Europe/Madrid";

    public static function getTimeZoneKeyValue()
    {
        $time_zones = [];
        foreach (self::timeZone() as $time_zone) {
            $time_zones[] = [
                "key" => __("time_zone." . $time_zone),
                "value" => $time_zone
            ];
        }
        return $time_zones;
    }

    private static function timeZone()
    {
        return [
            self::COLOMBIA,
            self::REPUBLICA_DOMINICANA,
            self::BRASIL_ACRE,
            self::BRASIL_DENORONHA,
            self::BRASIL_EAST,
            self::BRASIL_WEST,
            self::ARGENTINA,
            self::BOLIVIA,
            self::ECUADOR,
            self::PARAGUAY,
            self::PERU,
            self::NICARAGUA,
            self::COSTA_RICA,
            self::GUATEMALA,
            self::ESPAÑA,
        ];
    }
}
