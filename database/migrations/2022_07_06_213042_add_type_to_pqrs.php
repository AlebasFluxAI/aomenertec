<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\Pqr;

class AddTypeToPqrs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->enum('level', [
                Pqr::PQR_LEVEL_1,
                Pqr::PQR_LEVEL_2,
            ], Pqr::PQR_LEVEL_1)->nullable();
            $table->enum('type', [
                Pqr::PQR_TYPE_BILLING,
                Pqr::PQR_TYPE_PLATFORM,
                Pqr::PQR_TYPE_TECHNICIAN,
            ])->nullable();
            $table->enum('sub_type', [
                Pqr::PQR_SUB_TYPE_PLATFORM_ADMIN,
                Pqr::PQR_SUB_TYPE_ERROR,
                Pqr::PQR_SUB_TYPE_INVOICING,
                Pqr::PQR_SUB_TYPE_OVERRUN,
                Pqr::PQR_SUB_TYPE_PAYMENT_AGREE,
            ])->nullable();
            $table->enum('severity', [
                Pqr::PQR_SEVERITY_LOW,
                Pqr::PQR_SEVERITY_MEDIUM,
                Pqr::PQR_SEVERITY_HIGH,
            ])->nullable();

            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_identification')->nullable();
            $table->foreignId("technician_id")->nullable()->constrained();
            $table->string("client_code")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->dropColumn('subject');
            $table->dropColumn('description');
            $table->dropColumn('level');
            $table->dropColumn('type');
            $table->dropColumn('sub_type');
            $table->dropColumn('severity');
            $table->dropColumn('technician_id');

            $table->dropColumn('contact_name');
            $table->dropColumn('contact_phone');
            $table->dropColumn('contact_identification');
            $table->dropColumn('client_code');
        });
    }
}
