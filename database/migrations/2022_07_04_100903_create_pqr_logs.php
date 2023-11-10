<?php

use App\Models\V1\PqrLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqrLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqr_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("pqr_id")->constrained();
            $table->enum("activity_type", [
                PqrLog::ACTIVITY_TYPE_CHANGE_LEVEL,
                PqrLog::ACTIVITY_TYPE_CLOSE_TICKET,
                PqrLog::ACTIVITY_TYPE_OPEN_TICKET,
                PqrLog::ACTIVITY_TYPE_REOPEN_TICKET,
                PqrLog::ACTIVITY_TYPE_CHANGE_STATUS,
            ])->nullable();
            $table->json("before")->nullable();
            $table->json("after")->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->on('users')->references('id');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->on('users')->references('id');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->on('users')->references('id');
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
        Schema::dropIfExists('pqr_logs');
    }
}
