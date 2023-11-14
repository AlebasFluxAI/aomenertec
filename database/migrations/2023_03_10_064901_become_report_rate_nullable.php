<?php

use App\Http\Resources\V1\AlterEnumHelper;
use App\Models\V1\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BecomeReportRateNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AlterEnumHelper::alterEnum(new Client(), "report_rate", [
            Client::DAILY_RATE,
            Client::MONTHLY_RATE,
            Client::NONE,
        ]);

        Client::whereReportRate(Client::MONTHLY_RATE)->update([
            "report_rate" => Client::NONE,
        ]);
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
