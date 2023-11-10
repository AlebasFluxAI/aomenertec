<?php

use App\Models\V1\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table((new Invoice())->getTable(), function (Blueprint $table) {
            $table->enum("type", [
                Invoice::TYPE_CONSUMPTION,
                Invoice::TYPE_PLATFORM_USAGE,
            ])->default(Invoice::TYPE_PLATFORM_USAGE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function (Blueprint $table) {
            //
        });
    }
}
