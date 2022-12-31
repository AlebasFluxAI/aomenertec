<?php

namespace App\Http\Resources\V1;

use App\Events\UserNotificationEvent;
use ArrayAccess;
use NumberFormatter;

class Formatter
{
    public static function currencyFormat($money, $currency = "COP")
    {
        $fmt = new NumberFormatter('es_ES', NumberFormatter::CURRENCY);
        if (!$money) {
            return 0;
        }
        return ($fmt->formatCurrency($money, $currency));
    }

    public static function numberFormat($money)
    {
        if (!$money) {
            return 0;
        }
        return (number_format($money, 0));
    }
}
