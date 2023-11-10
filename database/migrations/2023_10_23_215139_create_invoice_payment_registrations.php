<?php

use App\Models\V1\InvoicePaymentRegistration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePaymentRegistrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payment_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id")->nullable()->constrained();
            $table->string("reference");
            $table->double("total")->default(0.0);
            $table->enum("payment_method", [
                InvoicePaymentRegistration::PAYMENT_METHOD_CASH,
                InvoicePaymentRegistration::PAYMENT_METHOD_CREDIT_CARD,
                InvoicePaymentRegistration::PAYMENT_METHOD_TRANSFER,
                InvoicePaymentRegistration::PAYMENT_METHOD_OTHER,
            ])->nullable();
            $table->string("bank")->nullable();
            $table->string("other_method")->nullable();
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
        Schema::dropIfExists('invoice_payment_registrations');
    }
}
