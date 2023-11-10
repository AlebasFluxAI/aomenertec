<?php

use App\Models\Model\V1\BillingService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId("network_operator_id")->constrained();
            $table->boolean("has_billable_pqr")->default(true);
            $table->boolean("has_billable_orders")->default(true);
            $table->boolean("has_billable_clients")->default(true);
            $table->double("pqr_price")->default(0.0);
            $table->double("orders_price")->default(0.0);
            $table->double("initial_package_pqr_price")->default(0.0);
            $table->double("initial_package_orders_price")->default(0.0);
            $table->enum("currency", [
                BillingService::USD,
                BillingService::COP,
            ])->default(BillingService::COP);
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
        Schema::dropIfExists('billing_services');
    }
}
