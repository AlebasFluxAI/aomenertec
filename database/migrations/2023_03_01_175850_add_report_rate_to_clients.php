<?php

use App\Models\V1\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportRateToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum("report_rate", [
                Client::MONTHLY_RATE,
                Client::DAILY_RATE
            ])->default(Client::MONTHLY_RATE);
            $table->json("report_variables")->nullable();
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
            $table->dropColumn("report_rate");
            $table->dropColumn("report_variables");
        });
    }
}
