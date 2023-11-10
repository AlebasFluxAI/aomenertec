<?php

use App\Models\V1\Client;
use App\Models\V1\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BecomeEmailsToLowercase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (User::get() as $user) {
            $email = $user->email;
            $user->update([
                "email" => strtolower($email)
            ]);
        }
        foreach (Client::get() as $client) {
            $email = $client->email;
            $client->update([
                "email" => strtolower($email)
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lowercase', function (Blueprint $table) {
            //
        });
    }
}
