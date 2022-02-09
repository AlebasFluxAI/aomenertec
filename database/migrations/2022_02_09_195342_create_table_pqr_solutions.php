<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\PqrSolution;
class CreateTablePqrSolutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_pqr_solutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pqr_id')->constrained();
            $table->enum('status',[PqrSolution::STATUS_ACCEPTED,PqrSolution::STATUS_PENDING,PqrSolution::STATUS_REJECTED]);
            $table->text('solution')->nullable();
            $table->enum('accepted_type',[PqrSolution::ACCEPTED_TYPE_NETWORK_OPERATOR,PqrSolution::ACCEPTED_TYPE_USER]);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('pending_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('accepted_by')->nullable();

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
        Schema::dropIfExists('table_pqr_solutions');
    }
}
