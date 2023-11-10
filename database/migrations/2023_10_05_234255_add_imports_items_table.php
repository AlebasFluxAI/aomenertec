<?php

use App\Models\V1\Import;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("import_id")->constrained();
            $table->text("error")->nullable();
            $table->integer("item_index")->nullable();
            $table->enum("status", [
                Import::STATUS_PROCESSING,
                Import::STATUS_ERROR,
                Import::STATUS_PENDING,
                Import::STATUS_COMPLETED,
            ])->default(Import::STATUS_PENDING);
            $table->nullableMorphs('importable');
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
        Schema::dropIfExists('import_items');
    }
}
