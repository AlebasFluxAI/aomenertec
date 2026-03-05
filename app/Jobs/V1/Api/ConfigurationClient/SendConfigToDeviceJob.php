<?php

namespace App\Jobs\V1\Api\ConfigurationClient;

use App\Http\Repositories\ConfigurationClient\ConfigClientRepository;
use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Sends a configuration command to a device via MQTT.
 *
 * This job runs in background to avoid corrupting the Livewire session
 * (the Request facade swap required by ConfigClientRepositoryImpl
 * destroys session state when done inline in a Livewire context).
 */
class SendConfigToDeviceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $requestDetails;
    public string $eventName;

    public function __construct(array $requestDetails, string $eventName)
    {
        $this->requestDetails = $requestDetails;
        $this->eventName = $eventName;
    }

    public function handle(): void
    {
        try {
            $httpMethod = strtoupper($this->requestDetails['method']);
            $body = $this->requestDetails['body'];
            $serial = $body['serial'] ?? null;

            // Build the internal URL path
            $urlParts = parse_url($this->requestDetails['url']);
            $path = $urlParts['path'] ?? '/';

            // Create a synthetic request that the existing code can read via Request facade
            if ($httpMethod === 'GET') {
                $queryString = http_build_query($body);
                $uri = $path . '?' . $queryString;
                $internalRequest = HttpRequest::create($uri, 'GET');
            } else {
                $internalRequest = HttpRequest::create($path, 'POST', $body, [], [], [
                    'CONTENT_TYPE' => 'application/json',
                ], json_encode($body));
                $internalRequest->headers->set('Content-Type', 'application/json');
            }
            $internalRequest->headers->set('x-api-key', $this->requestDetails['apiKey']);

            // Save original request and swap
            $originalRequest = app('request');

            try {
                app()->instance('request', $internalRequest);

                // Replicate EventQueueValidatorMiddleware logic
                $eventType = EventLog::getEvents($path);
                $client = Client::getClientFromSerial($serial);

                if ($eventType && $eventType !== EventLog::EVENT_DATE_RANGE && $client) {
                    // Rate limit check (45s window)
                    $lastEvent = EventLog::whereEvent($eventType)
                        ->whereClientId($client->id)
                        ->whereStatus(EventLog::STATUS_CREATED)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if ($lastEvent) {
                        $eventDate = \Carbon\Carbon::create($lastEvent->created_at);
                        if (now()->diffInSeconds($eventDate) <= 45) {
                            Log::error("SendConfigToDeviceJob: Rate-limited {$this->eventName} for serial {$serial}");
                            return;
                        }
                        $lastEvent->status = EventLog::STATUS_ERROR;
                        $lastEvent->save();
                    }
                }

                // Create AckLog and EventLog
                $ackLog = AckLog::create(["serial" => $serial]);
                $internalRequest->headers->set(EventLog::API_EVENT_HEADER, $eventType);
                $internalRequest->headers->set(Client::CLIENT_HEADER, $client ? $client->id : null);
                $eventLog = EventLog::createEvent($ackLog, json_encode($body), EventLog::CLIENT_MAIN_SERVER_REQUEST, json_encode($body), null);
                $internalRequest->headers->set(EventLog::EVENT_LOG_HEADER, $eventLog);
                $internalRequest->headers->set(EventLog::EVENT_LOG_HEADER_ID, $eventLog->id);
                $internalRequest->headers->set(AckLog::ACK_LOG_HEADER, $ackLog);
                $internalRequest->headers->set("serial", $serial);

                // Re-bind the modified request
                app()->instance('request', $internalRequest);

                // Call repository directly (binary packing + MQTT publish + dispatch CheckAckLogJob)
                $repository = app(ConfigClientRepository::class);
                $result = $repository->runService();

                Log::error("SendConfigToDeviceJob: Config sent {$this->eventName} for serial {$serial}");
            } finally {
                app()->instance('request', $originalRequest);
            }
        } catch (\Throwable $e) {
            $serial = $this->requestDetails['body']['serial'] ?? 'unknown';
            Log::error("SendConfigToDeviceJob: Exception {$this->eventName} for serial {$serial}: {$e->getMessage()}");
        }
    }
}
