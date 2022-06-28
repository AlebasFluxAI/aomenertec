<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDigitalOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_digital_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->integer('number');
            $table->string('name');
            $table->boolean('status');
            $table->enum("control_type", [
                \App\Models\V1\ClientDigitalOutput::AUTOMATIC,
                \App\Models\V1\ClientDigitalOutput::MANUAL
            ])->default(\App\Models\V1\ClientDigitalOutput::MANUAL);
            $table->enum("output_type", [
                \App\Models\V1\ClientDigitalOutput::NC,
                \App\Models\V1\ClientDigitalOutput::NO
            ])->default(\App\Models\V1\ClientDigitalOutput::NO);
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
        Schema::dropIfExists('client_digital_outputs');
    }
}
