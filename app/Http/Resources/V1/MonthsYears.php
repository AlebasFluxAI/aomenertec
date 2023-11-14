<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;

class MonthsYears extends Singleton
{
    public static function months()
    {
        return [
            ["value" => 0, "key" => ""],
            ["value" => 1, "key" => "Enero"],
            ["value" => 2, "key" => "Febrero"],
            ["value" => 3, "key" => "Marzo"],
            ["value" => 4, "key" => "Abril"],
            ["value" => 5, "key" => "Mayo"],
            ["value" => 6, "key" => "Junio"],
            ["value" => 7, "key" => "Julio"],
            ["value" => 8, "key" => "Agosto"],
            ["value" => 9, "key" => "Septiembre"],
            ["value" => 10, "key" => "Octubre"],
            ["value" => 11, "key" => "Noviembre"],
            ["value" => 12, "key" => "Diciembre"]
        ];

    }

    public static function years()
    {
        return [
            ["value" => 0, "key" => ""],
            ["key" => "2023", "value" => 2023],
            ["key" => "2024", "value" => 2024],
            ["key" => "2025", "value" => 2025],
            ["key" => "2026", "value" => 2026],
            ["key" => "2027", "value" => 2027],
            ["key" => "2028", "value" => 2028],
        ];
    }


}
