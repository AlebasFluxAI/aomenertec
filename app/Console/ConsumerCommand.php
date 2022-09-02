<?php

namespace App\Console;

use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use FlixTech\AvroSerializer\Objects\RecordSerializer;
use FlixTech\SchemaRegistryApi\Registry\BlockingRegistry;
use FlixTech\SchemaRegistryApi\Registry\Cache\AvroObjectCacheAdapter;
use FlixTech\SchemaRegistryApi\Registry\CachedRegistry;
use FlixTech\SchemaRegistryApi\Registry\PromisingRegistry;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;

class ConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kafka consumer';

    /**
     * Execute the console command.
     *
     * @return void
     */

    public function handle()
    {
        $consumer = Kafka::createConsumer(["real_time_topic"], 'monolith', "3.138.63.140:9092")
            ->withHandler(function (KafkaConsumerMessage $message) {
                print(json_encode($message->getBody()) . "\n");
                dispatch(new PushRealTimeMicrocontrollerDataJob($message->getBody()));
            })
            ->build();
        $consumer->consume();
    }


}
