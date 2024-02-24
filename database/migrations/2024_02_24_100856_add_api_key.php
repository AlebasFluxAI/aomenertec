<?php

use App\Models\V1\Api\ApiKey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string("api_key")->unique();
            $table->timestamp("expiration");
            $table->foreignId("user_id")->constrained();
            $table->enum("status", [
                ApiKey::STATUS_ENABLED,
                ApiKey::STATUS_DISABLED,
            ])->default(ApiKey::STATUS_DISABLED);
            $table->string("security_header_value")->nullable();
            $table->string("security_header_key")->nullable();
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
        Schema::dropIfExists('api_keys');
    }
}
