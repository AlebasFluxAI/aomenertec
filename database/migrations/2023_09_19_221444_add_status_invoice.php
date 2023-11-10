<?php

use App\Http\Resources\V1\AlterEnumHelper;
use App\Models\V1\Invoice;
use Illuminate\Database\Migrations\Migration;

class AddStatusInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        AlterEnumHelper::alterEnum(new Invoice(), "payment_status", [
            Invoice::PAYMENT_STATUS_APPROVED,
            Invoice::PAYMENT_STATUS_DECLINED,
            Invoice::PAYMENT_STATUS_PENDING,
            Invoice::PAYMENT_STATUS_ERROR,
            Invoice::PAYMENT_STATUS_VOIDED,
        ], Invoice::PAYMENT_STATUS_PENDING);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
