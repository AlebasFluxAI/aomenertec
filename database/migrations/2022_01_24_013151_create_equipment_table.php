<?php

use App\Models\V1\Equipment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_type_id')->constrained();
            $table->string('serial');
            $table->string('name');
            $table->string('description');
            $table->enum('status', [Equipment::STATUS_NEW, Equipment::STATUS_REPAIRED, Equipment::STATUS_DISREPAIR, Equipment::STATUS_REPAIR, Equipment::STATUS_REPAIR_PENDING])->default(Equipment::STATUS_NEW);
            $table->boolean('assigned')->default(false);
            $table->foreignId("admin_id")->nullable()->constrained();
            $table->foreignId("network_operator_id")->nullable()->constrained();
            $table->foreignId("technician_id")->nullable()->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
