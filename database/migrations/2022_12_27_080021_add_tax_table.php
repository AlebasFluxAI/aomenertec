<?php

use App\Models\V1\Tax;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->double("percentage");
            $table->softDeletes();
            $table->timestamps();
        });

        Tax::create([
            "name" => "IVA",
            "description" => "Impuesto de valor agregado",
            "percentage" => 19
        ]);
        Tax::create([
            "name" => "Sin impuesto",
            "description" => "Sin impuesto",
            "percentage" => 0
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxes');
    }
}
