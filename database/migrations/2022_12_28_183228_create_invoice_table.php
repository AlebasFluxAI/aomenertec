<?php

use App\Models\V1\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("admin_id");
            $table->string("currency")->default("COP");
            $table->double("subtotal")->default(0.0);
            $table->double("total")->default(0.0);
            $table->double("tax_total")->default(0.0);
            $table->double("discount")->default(0.0);
            $table->text("pdf_url")->nullable();
            $table->text("notes")->nullable();
            $table->string("code")->nullable();
            $table->date("payment_date")->nullable();
            $table->date("expiration_date")->nullable();
            $table->enum("payment_status", [Invoice::PAYMENT_STATUS_DECLINED, Invoice::PAYMENT_STATUS_PENDING, Invoice::PAYMENT_STATUS_APPROVED, Invoice::PAYMENT_STATUS_ERROR, Invoice::PAYMENT_STATUS_VOIDED])->default(Invoice::PAYMENT_STATUS_PENDING);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
