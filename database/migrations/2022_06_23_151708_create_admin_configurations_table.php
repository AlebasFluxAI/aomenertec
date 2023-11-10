<?php

use App\Models\V1\AdminConfiguration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained();
            $table->integer('min_value');
            $table->integer('min_clients');
            $table->enum("coin", [
                AdminConfiguration::COP,
                AdminConfiguration::USD,
            ])->default(AdminConfiguration::COP);
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
        Schema::dropIfExists('admin_configurations');
    }
}
