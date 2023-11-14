<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;

class IndicativeHelper extends Singleton
{
    public const COLOMBIA = "+57";
    public const REPUBLICA_DOMINICANA_809 = "+809";
    public const REPUBLICA_DOMINICANA_849 = "+849";
    public const REPUBLICA_DOMINICANA_829 = "+829";
    public const BRASIL = "+55";
    public const ARGENTINA = "+54";
    public const BOLIVIA = "+591";
    public const ECUADOR = "+593";
    public const PARAGUAY = "+595";
    public const PERU = "+51";
    public const EEUU = "+1";
    public const MEXICO = "+52";
    public const NICARAGUA = "+505";
    public const COSTA_RICA = "+506";
    public const GUATEMALA = "+502";
    public const ESPAÑA = "+34";

    public static function getIndicativesKeyValue()
    {
        $indicatives = [];
        foreach (self::numericalIndicatives() as $indicative) {
            $indicatives[] = [
                "key" => __("indicatives." . $indicative),
                "value" => $indicative
            ];
        }
        return $indicatives;
    }

    private static function numericalIndicatives()
    {
        return [
            self::COLOMBIA,
            self::REPUBLICA_DOMINICANA_809,
            self::REPUBLICA_DOMINICANA_829,
            self::REPUBLICA_DOMINICANA_849,
            self::BRASIL,
            self::ARGENTINA,
            self::BOLIVIA,
            self::ECUADOR,
            self::PARAGUAY,
            self::PERU,
            self::EEUU,
            self::MEXICO,
            self::NICARAGUA,
            self::COSTA_RICA,
            self::GUATEMALA,
            self::ESPAÑA,
        ];
    }
}
