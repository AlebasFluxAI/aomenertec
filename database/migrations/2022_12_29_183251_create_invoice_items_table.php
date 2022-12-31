<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("invoice_id");
            $table->foreignId("billable_item_id")->nullable()->constrained();
            $table->text("notes")->nullable();
            $table->double("unit_total")->default(0.0);
            $table->double("subtotal")->default(0.0);
            $table->double("total")->default(0.0);
            $table->double("tax_percentage")->default(0.0);
            $table->double("tax_total")->default(0.0);
            $table->double("discount")->default(0.0);
            $table->integer("quantity")->default(0.0);
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
        Schema::dropIfExists('invoice_items');
    }
}
