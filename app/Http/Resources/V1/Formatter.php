<?php

namespace App\Http\Resources\V1;

class Formatter
{
    public static function currencyFormat($money, $currency = "COP")
    {
        return (number_format($money, 0)) . " " . $currency;
    }

    public static function numberFormat($money)
    {
        if (!$money) {
            return 0;
        }
        return (number_format($money, 3));
    }
}
