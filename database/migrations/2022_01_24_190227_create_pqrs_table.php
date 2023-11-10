´<?php

use App\Models\V1\Pqr;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqrs', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->text("detail");
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->enum('level', [
                Pqr::PQR_LEVEL_1,
                Pqr::PQR_LEVEL_2,
            ], Pqr::PQR_LEVEL_1)->nullable();
            $table->enum('type', [
                Pqr::PQR_TYPE_BILLING,
                Pqr::PQR_TYPE_PLATFORM,
                Pqr::PQR_TYPE_TECHNICIAN,
            ])->nullable();
            $table->enum('sub_type', [
                Pqr::PQR_SUB_TYPE_PLATFORM_ADMIN,
                Pqr::PQR_SUB_TYPE_ERROR,
                Pqr::PQR_SUB_TYPE_INVOICING,
                Pqr::PQR_SUB_TYPE_OVERRUN,
                Pqr::PQR_SUB_TYPE_PAYMENT_AGREE,
            ])->nullable();
            $table->enum('severity', [
                Pqr::PQR_SEVERITY_LOW,
                Pqr::PQR_SEVERITY_MEDIUM,
                Pqr::PQR_SEVERITY_HIGH,
            ])->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_identification')->nullable();
            $table->string("client_code")->nullable();
            $table->foreignId("client_id")->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId("network_operator_id")->nullable()->constrained();
            $table->foreignId("technician_id")->nullable()->constrained();
            $table->foreignId("support_id")->nullable()->constrained();
            $table->foreignId("supervisor_id")->nullable()->constrained();
            $table->enum("status", [Pqr::STATUS_CREATED, Pqr::STATUS_PROCESSING, Pqr::STATUS_RESOLVED, Pqr::STATUS_CLOSED]);
            $table->boolean("change_equipment")->default(false);
            $table->boolean("has_equipment_changed")->default(false);
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
        Schema::dropIfExists('pqrs');
    }
}
