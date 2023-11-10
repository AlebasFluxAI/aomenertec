<?php

use App\Models\V1\Equipment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasAssignationToEquipments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->boolean("has_admin")->default(false);
            $table->boolean("has_network_operator")->default(false);
            $table->boolean("has_technician")->default(false);
            $table->boolean("has_clients")->default(false);
        });
        foreach (Equipment::get() as $equipment) {
            if ($equipment->admin_id) {
                $equipment->has_admin = true;
            }
            if ($equipment->network_operator_id) {
                $equipment->has_network_operator = true;
            }
            if ($equipment->technician_id) {
                $equipment->has_technician = true;
            }
            if ($equipment->client_id) {
                $equipment->has_clients = true;
            }
            $equipment->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {
            //
        });
    }
}
