<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPersonTypeToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn("technicians", "person_type")) {
            Schema::table('technicians', function (Blueprint $table) {
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
            });
        }

        if (!Schema::hasColumn("sellers", "person_type")) {
            Schema::table('sellers', function (Blueprint $table) {
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
            });
        }

        if (!Schema::hasColumn("supports", "person_type")) {
            Schema::table('supports', function (Blueprint $table) {
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
            });
        }

        if (!Schema::hasColumn("admins", "person_type")) {
            Schema::table('admins', function (Blueprint $table) {
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
            });
        }

        if (!Schema::hasColumn("network_operators", "person_type")) {
            Schema::table('network_operators', function (Blueprint $table) {
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
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
