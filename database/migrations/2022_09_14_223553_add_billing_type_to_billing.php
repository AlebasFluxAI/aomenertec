<?php

use App\Models\V1\BillingInformation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingTypeToBilling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_informations', function (Blueprint $table) {
            $table->enum("type", [
                BillingInformation::BILLING_TYPE_POSTPAID,
                BillingInformation::BILLING_TYPE_PREPAID,
                BillingInformation::BILLING_TYPE_NONE
            ])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_informations', function (Blueprint $table) {
            $table->dropColumn("type");
        });
    }
}
