<?php

use App\Http\Resources\V1\AuditFieldMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricalClientEquipments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_client_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id")->constrained();
            $table->foreignId("equipment_id")->constrained();
            $table->unsignedBigInteger("before_equipment_id")->nullable();
            $table->foreignId("pqr_id")->nullable()->constrained();
            $table->string("notes")->nullable();
            $table->unsignedBigInteger('assigned_by_id')->nullable();
            $table->string('assigned_by_model')->nullable();
            AuditFieldMigration::auditoryField($table);
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
        Schema::dropIfExists('historical_client_equipments');
    }
}
