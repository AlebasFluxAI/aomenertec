<?php

use App\Models\V1\ClientType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ReseedClientTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("admin_prices")->delete();
        DB::table("admin_client_types")->delete();
        DB::table("clients")->update(["client_type_id" => null]);
        DB::table("client_type_equipment_types")->delete();
        DB::table("client_types")->delete();


        foreach (["ZNI Sistema fotovoltaico", "ZNI Convencional", "ZNI rural",
                     "SIN Convencional", "Monitoreo"
                 ] as $type) {
            ClientType::create([
                "type" => $type,
                "description" => $type
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
