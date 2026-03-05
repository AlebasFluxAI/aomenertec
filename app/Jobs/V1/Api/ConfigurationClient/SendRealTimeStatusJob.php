<?php

namespace App\Jobs\V1\Api\ConfigurationClient;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Http\Repositories\ConfigurationClient\ConfigClientRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Sends a set-status-real-time command to a device via MQTT in background.
 *
 * Replaces the blocking Http::get('http://localhost/...') pattern that
 * caused deadlocks on the single-threaded php artisan serve server.
 * This job runs on queue 'spot2' without waiting for ACK.
 */
class SendRealTimeStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $serial;
    public int $status;
    public string $apiKey;

    public function __construct(string $serial, int $status, string $apiKey)
    {
        $this->serial = $serial;
        $this->status = $status;
        $this->apiKey = $apiKey;
    }

    public function handle(): void
    {
        try {
            $path = config('aom.api_config_path') . '/set-status-real-time';
            $body = [
                'serial' => $this->serial,
                'status' => $this->status,
            ];

            $queryString = http_build_query($body);
            $uri = $path . '?' . $queryString;
            $internalRequest = HttpRequest::create($uri, 'GET');
            $internalRequest->headers->set('x-api-key', $this->apiKey);

            $originalRequest = app('request');

            try {
                app()->instance('request', $internalRequest);

                $eventType = EventLog::getEvents($path);
                $client = Client::getClientFromSerial($this->serial);

                if ($eventType && $client) {
                    $lastEvent = EventLog::whereEvent($eventType)
                        ->whereClientId($client->id)
                        ->whereStatus(EventLog::STATUS_CREATED)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if ($lastEvent) {
                        $eventDate = \Carbon\Carbon::create($lastEvent->created_at);
                        if (now()->diffInSeconds($eventDate) <= 45) {
                            Log::error("SendRealTimeStatusJob: Rate-limited for serial {$this->serial}");
                            return;
                        }
                        $lastEvent->status = EventLog::STATUS_ERROR;
                        $lastEvent->save();
                    }
                }

                $ackLog = AckLog::create(["serial" => $this->serial]);
                $internalRequest->headers->set(EventLog::API_EVENT_HEADER, $eventType);
                $internalRequest->headers->set(Client::CLIENT_HEADER, $client ? $client->id : null);
                $eventLog = EventLog::createEvent($ackLog, json_encode($body), EventLog::CLIENT_MAIN_SERVER_REQUEST, json_encode($body), null);
                $internalRequest->headers->set(EventLog::EVENT_LOG_HEADER, $eventLog);
                $internalRequest->headers->set(EventLog::EVENT_LOG_HEADER_ID, $eventLog->id);
                $internalRequest->headers->set(AckLog::ACK_LOG_HEADER, $ackLog);
                $internalRequest->headers->set("serial", $this->serial);

                app()->instance('request', $internalRequest);

                $repository = app(ConfigClientRepository::class);
                $repository->runService();

                Log::error("SendRealTimeStatusJob: real-time status={$this->status} sent for serial {$this->serial}");
            } finally {
                app()->instance('request', $originalRequest);
            }
        } catch (\Throwable $e) {
            Log::error("SendRealTimeStatusJob: Exception for serial {$this->serial}: {$e->getMessage()}");
        }
    }
}
