<?php

namespace App\Console;

use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\SaveAlertDataJob;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Jobs\V1\Api\ConfigurationClient\SetConfigJob;
use App\ModulesAux\MQTT;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\MqttClientException;

class ConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MQTT consumer - subscribes to IoT device topics and dispatches processing jobs';

    /**
     * Cache key used by the health check to verify the consumer is alive.
     */
    public const HEARTBEAT_CACHE_KEY = 'mqtt:consumer:heartbeat';

    /**
     * How often (in seconds) the consumer writes its heartbeat.
     */
    private const HEARTBEAT_INTERVAL = 30;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('MQTT consumer starting — subscribing to all topics...');

        try {
            $mqtt = MQTT::connection('default', 'client_consumer_princi');
        } catch (MqttClientException $e) {
            Log::error('MQTT consumer failed to connect to broker: ' . $e->getMessage());
            // Exit with non-zero so Supervisor restarts us
            return 1;
        }

        $lastHeartbeat = 0;

        $mqtt->subscribe('v1/mc/data', function (string $topic, string $message) {
            $pack = base64_encode($message);
            dispatch(new SaveMicrocontrollerDataJob($pack, false))->onQueue('spot');
        }, 2);

        $mqtt->subscribe('v1/mc/alert', function (string $topic, string $message) {
            $pack = base64_encode($message);
            dispatch(new SaveAlertDataJob($pack, false))->onQueue('default');
        }, 0);

        $mqtt->subscribe('v1/mc/alert_control', function (string $topic, string $message) {
            $pack = base64_encode($message);
            dispatch(new SaveAlertDataJob($pack, true))->onQueue('default');
        }, 0);

        $mqtt->subscribe('v1/mc/ack', function (string $topic, string $message) {
            // Last Will llega como hex string desde el broker; ACKs normales como binario.
            // Detectar hex: si todos los chars son hex válidos y longitud es par, es hex string.
            if (strlen($message) > 0 && strlen($message) % 2 === 0 && ctype_xdigit($message)) {
                $hex = $message;
            } else {
                $hex = bin2hex($message);
            }
            dispatch(new SetConfigJob($hex))->onQueue('spot1');
        }, 0);

        $mqtt->subscribe('mc/data', function (string $topic, string $message) {
            dispatch(new SaveMicrocontrollerDataJob($message, false))->onQueue('spot');
        }, 2);

        // QoS 1 (at least once) — prevents message loss on silent disconnects.
        // With QoS 0 (fire and forget), the broker drops messages if the consumer
        // is temporarily unreachable, which was the root cause of real-time data loss.
        $mqtt->subscribe('v1/mc/real_time', function (string $topic, string $message) {
            $pack = base64_encode($message);
            dispatch(new PushRealTimeMicrocontrollerDataJob($pack))->onQueue('default');
        }, 1);

        Log::info('MQTT consumer subscribed to all topics — entering loop.');

        // Register a loop event handler that writes a heartbeat to cache.
        // The health check command reads this to determine if the consumer is alive.
        $mqtt->registerLoopEventHandler(function () use (&$lastHeartbeat) {
            $now = time();
            if (($now - $lastHeartbeat) >= self::HEARTBEAT_INTERVAL) {
                $lastHeartbeat = $now;
                try {
                    Cache::put(self::HEARTBEAT_CACHE_KEY, [
                        'timestamp' => $now,
                        'pid' => getmypid(),
                    ], now()->addMinutes(5));
                } catch (\Throwable $e) {
                    // Redis might be temporarily unavailable — don't crash the consumer
                }
            }
        });

        $mqtt->loop();
    }
}
