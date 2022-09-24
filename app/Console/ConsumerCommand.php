<?php

namespace App\Console;

use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\SaveAlertDataJob;
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
use PhpMqtt\Client\Facades\MQTT;

class
ConsumerCommand extends Command
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
        $mqtt = MQTT::connection();

        $mqtt->subscribe('mc/real_time', function (string $topic, string $message) {
            dispatch(new PushRealTimeMicrocontrollerDataJob($message));
        }, 0);
        $mqtt->subscribe('mc/data', function (string $topic, string $message) {
            dispatch(new SaveMicrocontrollerDataJob($message));

        }, 1);
        $mqtt->subscribe('mc/alert', function (string $topic, string $message) {
            dispatch(new SaveMicrocontrollerDataJob($message));
            dispatch(new SaveAlertDataJob($message));
        }, 1);
        $mqtt->loop();

    }


}
