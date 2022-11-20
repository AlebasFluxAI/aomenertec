<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndicativeToUsersCellphone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("indicative")->default("+57");

        });
        Schema::table('super_admins', function (Blueprint $table) {
            $table->string("indicative")->default("+57");
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->string("indicative")->default("+57");
        });
        Schema::table('network_operators', function (Blueprint $table) {
            $table->string("indicative")->default("+57");
        });
        Schema::table('technicians', function (Blueprint $table) {
            $table->string("indicative")->default("+57");
        });
        Schema::table('supports', function (Blueprint $table) {
            $table->string("indicative")->default("+57");
        });
        Schema::table('sellers', function (Blueprint $table) {
            $table->string("indicative")->default("+57");
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->string("indicative")->default("+57");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("indicative");

        });
        Schema::table('super_admins', function (Blueprint $table) {
            $table->dropColumn("indicative");
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn("indicative");
        });
        Schema::table('network_operators', function (Blueprint $table) {
            $table->dropColumn("indicative");
        });
        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn("indicative");
        });
        Schema::table('supports', function (Blueprint $table) {
            $table->dropColumn("indicative");
        });
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn("indicative");
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn("indicative");
        });
    }
}
