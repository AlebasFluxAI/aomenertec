<?php

use App\Models\V1\WorkOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id");
            $table->foreignId("pqr_id")->nullable();
            $table->enum("type", [
                WorkOrder::WORK_ORDER_TYPE_CORRECTIVE_MAINTENANCE,
                WorkOrder::WORK_ORDER_TYPE_PREVENTIVE_MAINTENANCE,
                WorkOrder::WORK_ORDER_TYPE_INSTALLATION,
                WorkOrder::WORK_ORDER_TYPE_REPLACE,
            ]);
            $table->string("created_by_type");
            $table->bigInteger("created_by_id");
            $table->foreignId("technician_id");
            $table->enum("status", [
                WorkOrder::WORK_ORDER_STATUS_OPEN,
                WorkOrder::WORK_ORDER_STATUS_CLOSED,
                WorkOrder::WORK_ORDER_STATUS_IN_PROGRESS,
            ]);
            $table->text("description")->nullable();
            $table->text("solution_description")->nullable();
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
        Schema::dropIfExists('work_orders');
    }
}
