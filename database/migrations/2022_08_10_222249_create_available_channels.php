<?php

use App\Models\V1\Admin;
use App\Models\V1\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvailableChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_channels', function (Blueprint $table) {
            $table->id();
            $table->string("channel")->nullable();
            $table->string("channel_class")->nullable();
            $table->boolean("enabled")->default(false);
            $table->morphs("model");
            $table->softDeletes();
            $table->timestamps();
        });

        foreach (Admin::get() as $admin) {
            $admin->channels()->create([
                "channel" => \App\Models\V1\AvailableChannel::CHANNEL_EMAIL,
                "channel_class" => "mail",
                "enabled" => true
            ]);
            $admin->channels()->create([
                "channel" => \App\Models\V1\AvailableChannel::CHANNEL_WHATSAPP,
                "channel_class" => \App\Channels\WhatsAppChannel::class,
                "enabled" => true
            ]);
        }


        foreach (Client::get() as $client) {
            $client->channels()->create([
                "channel" => \App\Models\V1\AvailableChannel::CHANNEL_EMAIL,
                "channel_class" => "mail",
                "enabled" => true
            ]);
            $client->channels()->create([
                "channel" => \App\Models\V1\AvailableChannel::CHANNEL_WHATSAPP,
                "channel_class" => \App\Channels\WhatsAppChannel::class,
                "enabled" => true
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
        Schema::dropIfExists('available_channels');
    }
}
