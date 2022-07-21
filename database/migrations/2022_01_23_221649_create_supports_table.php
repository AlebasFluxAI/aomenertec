<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->boolean('enabled')->default(true);
            $table->string('identification')->unique();
            $table->enum("person_type", [
                \App\Models\V1\User::PERSON_TYPE_JURIDICAL,
                \App\Models\V1\User::PERSON_TYPE_NATURAL
            ])->default(\App\Models\V1\User::PERSON_TYPE_NATURAL);
            $table->enum("identification_type", [
                \App\Models\V1\User::IDENTIFICATION_TYPE_CC,
                \App\Models\V1\User::IDENTIFICATION_TYPE_CE,
                \App\Models\V1\User::IDENTIFICATION_TYPE_PEP,
                \App\Models\V1\User::IDENTIFICATION_TYPE_PP,
                \App\Models\V1\User::IDENTIFICATION_TYPE_NIT,
                \App\Models\V1\User::IDENTIFICATION_TYPE_OTHER,
            ])->default(\App\Models\V1\User::IDENTIFICATION_TYPE_CC);
            $table->string('billing_name')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string("address")->nullable();
            $table->string("address_details")->nullable();
            $table->double("latitude")->nullable();
            $table->double("longitude")->nullable();
            $table->json("here_maps")->nullable();
            $table->string("postal_code")->nullable();
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->boolean("pqr_available")->nullable()->default(false);
            $table->foreignId('user_id')->nullable()->constrained();
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
        Schema::dropIfExists('supports');
    }
}
