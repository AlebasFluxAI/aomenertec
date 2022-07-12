´<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\Pqr;

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
            $table->text("detail");
            $table->foreignId('equipment_id')->nullable()->constrained();
            $table->foreignId('pqr_type_id')->constrained();
            $table->foreignId('network_operator_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId("support_id")->constrained();
            $table->enum("status", [Pqr::STATUS_CREATED, Pqr::STATUS_PROCESSING, Pqr::STATUS_RESOLVED, Pqr::STATUS_CLOSED]);
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
        Schema::dropIfExists('pqrs');
    }
}
