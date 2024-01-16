<?php

use App\Models\V1\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn("clients", "status")) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->enum("status", [
                Client::CLIENT_STATUS_DISABLED,
                Client::CLIENT_STATUS_ENABLED,
            ])->default(Client::CLIENT_STATUS_ENABLED);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
