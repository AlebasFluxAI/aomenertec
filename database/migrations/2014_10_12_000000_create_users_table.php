<?php

use App\Models\V1\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->boolean('enabled')->default(true);
            $table->string('identification')->unique();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->enum('type', [
                User::TYPE_ADMIN,
                User::TYPE_SUPER_ADMIN,
                User::TYPE_NETWORK_OPERATOR,
                User::TYPE_SELLER,
                User::TYPE_SUPERVISOR,
                User::TYPE_SUPPORT,
                User::TYPE_TECHNICIAN
            ]);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
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
        Schema::dropIfExists('users');
    }
}
