<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\Equipment;

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
            $table->string('description');
            $table->enum('status', [Equipment::STATUS_NEW, Equipment::STATUS_REPAIRED, Equipment::STATUS_DISREPAIR, Equipment::STATUS_REPAIR])->default(Equipment::STATUS_NEW);
            $table->boolean('assigned')->default(false);
            $table->timestamps();
            $table->softDeletes();
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
