<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\PqrMessage;

class AddDataToPqrMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_messages', function (Blueprint $table) {
            $table->foreignId("pqr_id")->nullable()->constrained();
            $table->enum("sender_type", [PqrMessage::SENDER_TYPE_CLIENT,
                PqrMessage::SENDER_TYPE_NETWORK_OPERATOR,
                PqrMessage::SENDER_TYPE_SUPERVISOR,
                PqrMessage::SENDER_TYPE_USER])->nullable();
            $table->bigInteger("sent_by")->nullable();
        });

        Schema::table('pqr_messages', function (Blueprint $table) {
            $table->text("message")->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqr_messages', function (Blueprint $table) {
            $table->dropColumn("pqr_id");
            $table->dropColumn("sender_type");
            $table->dropColumn("sent_by");
        });
    }
}
