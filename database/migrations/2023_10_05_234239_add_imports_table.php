<?php

use App\Models\V1\Import;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("url")->nullable();
            $table->string("file_name")->nullable();
            $table->string("type")->nullable();
            $table->enum("status", [
                Import::STATUS_PROCESSING,
                Import::STATUS_ERROR,
                Import::STATUS_PENDING,
                Import::STATUS_COMPLETED,
            ])->default(Import::STATUS_PENDING);
            $table->morphs('auditable');
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
        Schema::dropIfExists('imports');
    }
}
