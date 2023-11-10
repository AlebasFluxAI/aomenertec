<?php

use App\Models\V1\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEquipmentsToTechnicianAndOperators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Client::get() as $client) {
            foreach ($client->equipments as $equipment) {
                $equipment->update([
                    "technician_id" => $client->technician_id,
                    "network_operator_id" => $client->technician->first() ? $client->technician->first()->technician->network_operator_id : null,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('technician_and_operators', function (Blueprint $table) {
            //
        });
    }
}
