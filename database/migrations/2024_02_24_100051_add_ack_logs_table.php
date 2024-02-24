<?php

use App\Models\V1\Api\AckLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAckLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ack_logs', function (Blueprint $table) {
            $table->id();
            $table->string("serial")->nullable();
            $table->enum("status", [
                AckLog::STATUS_PENDING,
                AckLog::STATUS_SUCCESS,
                AckLog::STATUS_EXPIRED,
            ])->default(AckLog::STATUS_PENDING);
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
        Schema::dropIfExists('ack_logs');
    }
}
