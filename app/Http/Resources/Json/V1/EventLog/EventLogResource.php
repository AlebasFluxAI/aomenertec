<?php

namespace App\Http\Resources\Json\V1\EventLog;

use Illuminate\Http\Resources\Json\JsonResource;

class EventLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "transaction_id" => $this->ack_log_id,
            "request_type" => $this->request_type,
            "event" => $this->event,
            "request_endpoint" => $this->request_endpoint,
            "webhook" => $this->webhook,
            "request_json" => $this->request_json,
            "response_json" => $this->response_json,
            "status" => $this->status,
            "serial" => $this->serial,
            "name" => $this->name,
            "request_at" => $this->created_at,
            "response_at" => $this->updated_at,
        ];
    }
}
