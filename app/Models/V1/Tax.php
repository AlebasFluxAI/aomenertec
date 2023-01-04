<?php

namespace App\Models\V1;

use App\Models\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;

    protected $fillable = [
        "name",
        "description",
        "percentage"
    ];

    public static function taxesAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el impuesto..",
                "value" => null
            ]],
            (Tax::get()->map(function ($tax) {
                return [
                    "key" => $tax->id . "- " . $tax->name . "- " . $tax->percentage . " %",
                    "value" => $tax->id,
                ];
            }))->toArray()
        ));
    }

}
