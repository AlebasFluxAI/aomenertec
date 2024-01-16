<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWikiTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wiki_trees', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("content");
            $table->boolean("enabled")->default(false);
            $table->unsignedInteger('parent_id')->unsigned()->nullable();
            $table->foreign("parent_id")
                ->references("id")
                ->on("wiki_trees");
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
        Schema::dropIfExists('wiki_trees');
    }
}
