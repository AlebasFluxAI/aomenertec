<?php

use App\Models\V1\PqrMessage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqrMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqr_messages', function (Blueprint $table) {
            $table->id();
            $table->text("message")->nullable();
            $table->foreignId("pqr_id")->nullable()->constrained();
            $table->enum("sender_type", [PqrMessage::SENDER_TYPE_CLIENT,
                PqrMessage::SENDER_TYPE_NETWORK_OPERATOR,
                PqrMessage::SENDER_TYPE_SUPERVISOR,
                PqrMessage::SENDER_TYPE_USER])->nullable();
            $table->bigInteger("sent_by")->nullable();
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
        Schema::dropIfExists('pqr_messages');
    }
}
