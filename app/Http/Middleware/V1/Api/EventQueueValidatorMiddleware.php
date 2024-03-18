<?php

namespace App\Http\Middleware\V1\Api;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use Closure;
use Illuminate\Http\Request;

class EventQueueValidatorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @aram \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $event = EventLog::getEvents($request->getRequestUri());
        $serial = $request->input("serial");
        if ($event) {
            $client = Client::getClientFromSerial($serial);
            if ($client != null) {
                if ($event != EventLog::EVENT_DATE_RANGE) {
                    if (EventLog::whereEvent($event)
                        ->whereClientId($client->id)
                        ->whereStatus(EventLog::STATUS_CREATED)
                        ->exists()) {
                        abort(429, "Evento del mismo tipo en proceso");
                    }
                }
            }

            $ackLog = AckLog::create(["serial" => $serial]);
            $request->headers->set(EventLog::API_EVENT_HEADER, $event);

            $request->headers->set(Client::CLIENT_HEADER, $client ? $client->id : null);
            $eventLog = EventLog::createEvent($ackLog, json_encode($request->all()), EventLog::CLIENT_MAIN_SERVER_REQUEST, json_encode($request->json), null);
            $request->headers->set(EventLog::EVENT_LOG_HEADER, $eventLog);
            $request->headers->set(EventLog::EVENT_LOG_HEADER_ID, $eventLog->id);
            $request->headers->set(AckLog::ACK_LOG_HEADER, $ackLog);
            $request->headers->set("serial", $serial);
        }

        return $next($request);
    }
}
