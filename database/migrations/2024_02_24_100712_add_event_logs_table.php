<?php

use App\Models\V1\Api\EventLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("event")->nullable();
            $table->string("client_id")->nullable();
            $table->string("request_endpoint")->nullable();
            $table->json("request_json")->nullable();
            $table->enum("request_type", [
                EventLog::CLIENT_MAIN_SERVER_REQUEST,
                EventLog::MAIN_SERVER_CLIENT_RESPONSE,
                EventLog::MAIN_SERVER_MC_REQUEST
            ])->nullable();
            $table->json("response_json")->nullable();
            $table->string("webhook")->nullable();
            $table->enum("status", [
                EventLog::STATUS_CREATED,
                EventLog::STATUS_ERROR,
                EventLog::STATUS_SUCCESSFUL,
            ])->default(EventLog::STATUS_CREATED);
            $table->foreignId("ack_log_id")->nullable()->constrained();
            $table->string("serial")->nullable();
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
        Schema::dropIfExists('event_logs');
    }
}
