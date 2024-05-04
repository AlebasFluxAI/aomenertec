<?php

namespace App\Http\Middleware\V1\Api;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use Carbon\Carbon;
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
                if($event != EventLog::EVENT_DATE_RANGE) {
                    $last_event = EventLog::whereEvent($event)
                        ->whereClientId($client->id)
                        ->whereStatus(EventLog::STATUS_CREATED)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if ($last_event) {
                        $event_date = Carbon::create($last_event->created_at);
                        $now = Carbon::now();
                        if ($now->diffInSeconds($event_date) > 45){
                            $last_event->status = EventLog::STATUS_ERROR;
                            $last_event->save();
                        }else {
                            abort(429, "Evento del mismo tipo en proceso");
                        }
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
