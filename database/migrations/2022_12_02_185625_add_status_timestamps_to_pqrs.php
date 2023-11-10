<?php

use App\Models\V1\Pqr;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusTimestampsToPqrs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->timestamp("status_" . Pqr::STATUS_CREATED . "_at")->nullable();
            $table->timestamp("status_" . Pqr::STATUS_PROCESSING . "_at")->nullable();
            $table->timestamp("status_" . Pqr::STATUS_RESOLVED . "_at")->nullable();
            $table->timestamp("status_" . Pqr::STATUS_CLOSED . "_at")->nullable();

            $table->unsignedBigInteger("status_" . Pqr::STATUS_CREATED . "_by")->nullable();
            $table->foreign("status_" . Pqr::STATUS_CREATED . "_by")->on('users')->references('id');
            $table->unsignedBigInteger("status_" . Pqr::STATUS_PROCESSING . "_by")->nullable();
            $table->foreign("status_" . Pqr::STATUS_PROCESSING . "_by")->on('users')->references('id');
            $table->unsignedBigInteger("status_" . Pqr::STATUS_RESOLVED . "_by")->nullable();
            $table->foreign("status_" . Pqr::STATUS_RESOLVED . "_by")->on('users')->references('id');
            $table->unsignedBigInteger("status_" . Pqr::STATUS_CLOSED . "_by")->nullable();
            $table->foreign("status_" . Pqr::STATUS_CLOSED . "_by")->on('users')->references('id');
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
            $table->dropColumn("status_" . Pqr::STATUS_CREATED . "_at");
            $table->dropColumn("status_" . Pqr::STATUS_PROCESSING . "_at");
            $table->dropColumn("status_" . Pqr::STATUS_RESOLVED . "_at");
            $table->dropColumn("status_" . Pqr::STATUS_CLOSED . "_at");
            $table->dropColumn("status_" . Pqr::STATUS_CREATED . "_by");
            $table->dropColumn("status_" . Pqr::STATUS_PROCESSING . "_by");
            $table->dropColumn("status_" . Pqr::STATUS_RESOLVED . "_by");
            $table->dropColumn("status_" . Pqr::STATUS_CLOSED . "_by");
        });
    }
}
