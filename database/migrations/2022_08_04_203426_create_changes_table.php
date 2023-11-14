<?php

use App\Models\V1\Change;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changes', function (Blueprint $table) {
            $table->id();
            $table->json("before")->default(null);
            $table->json("after")->default(null);
            $table->json("delta")->default(null);
            $table->enum("type", [
                Change::CHANGE_TYPE_DELETED,
                Change::CHANGE_TYPE_UPDATED,
                Change::CHANGE_TYPE_CREATED,
            ]);
            $table->morphs("model");
            $table->foreignId("user_id")->nullable()->constrained();
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
        Schema::dropIfExists('changes');
    }
}
