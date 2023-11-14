<?php

use App\Models\V1\PqrMessage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToPqrMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_messages', function (Blueprint $table) {
            $table->enum("type", [
                PqrMessage::MESSAGE_TYPE_REGULAR,
                PqrMessage::MESSAGE_TYPE_CLOSER
            ])->default(PqrMessage::MESSAGE_TYPE_REGULAR);
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
            $table->dropColumn("type");
        });
    }
}
