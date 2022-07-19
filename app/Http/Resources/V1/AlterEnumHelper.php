<?php

namespace App\Http\Resources\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterEnumHelper
{
    public static function alterEnum(Model $model, $field, array $enumOptions, $default = null)
    {
        $table = $model->getTable();
        $data_aux = [];
        DB::table($table)->orderBy('id', 'asc')->chunk(1000, function ($data) use (&$data_aux, $field) {
            foreach ($data as $row) {
                $data_aux[$row->id] = $row->{$field};
            }
        });

        Schema::table($table, function (Blueprint $table) use ($field) {
            $table->dropColumn($field);
        });

        Schema::table($table, function (Blueprint $table) use ($field, $enumOptions, $default) {
            if ($default) {
                $table->enum($field, $enumOptions)->default($default);

                return;
            }
            $table->enum($field, $enumOptions)->default($enumOptions[0]);
        });

        foreach ($data_aux as $index => $data) {
            DB::table($table)->whereId($index)->update([
                $field => is_null($data) ? $default : $data,
            ]);
        }
    }
}
