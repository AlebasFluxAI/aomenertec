<?php

use App\Http\Resources\V1\AuditFieldMigration;
use App\Models\V1\ClientRecharge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientRecharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_recharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id")->constrained();
            $table->foreignId("seller_id")->nullable()->constrained();
            $table->foreignId("network_operator_id")->nullable()->constrained();

            $table->enum("type", [
                ClientRecharge::PURCHASE_TYPE_ONLINE,
                ClientRecharge::PURCHASE_TYPE_STORE
            ])->default(ClientRecharge::PURCHASE_TYPE_ONLINE);


            $table->enum("payment_method", [
                ClientRecharge::PURCHASE_PAYMENT_METHOD_CASH,
                ClientRecharge::PURCHASE_PAYMENT_METHOD_ONLINE
            ])->default(ClientRecharge::PURCHASE_PAYMENT_METHOD_CASH);


            $table->enum("payment_status", [
                ClientRecharge::PURCHASE_PAYMENT_STATUS_PENDING,
                ClientRecharge::PURCHASE_PAYMENT_STATUS_COMPLETED,
                ClientRecharge::PURCHASE_PAYMENT_STATUS_CANCELLED
            ])->default(ClientRecharge::PURCHASE_PAYMENT_STATUS_PENDING);

            $table->double("kwh_price")->default(0.0);
            $table->double("kwh_credit")->default(0.0);
            $table->double("kwh_quantity")->default(0.0);
            $table->double("kwh_subsidy")->default(0.0);
            $table->double("total")->default(0.0);
            $table->boolean("notified")->default(false);
            $table->string("reference");
            AuditFieldMigration::auditoryField($table);
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
        Schema::dropIfExists('client_recharges');
    }
}
