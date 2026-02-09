<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeVaupesStratificationTypeNullableOnClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('vaupes_stratification_type')->nullable()->default(null)->change();
        });

        // Set existing default values to null (they were assigned by the old default, not by user choice)
        DB::table('clients')
            ->where('vaupes_stratification_type', 'Residencia 1')
            ->update(['vaupes_stratification_type' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore non-null values before reverting constraint
        DB::table('clients')
            ->whereNull('vaupes_stratification_type')
            ->update(['vaupes_stratification_type' => 'Residencia 1']);

        Schema::table('clients', function (Blueprint $table) {
            $table->string('vaupes_stratification_type')->nullable(false)->default('Residencia 1')->change();
        });
    }
}
