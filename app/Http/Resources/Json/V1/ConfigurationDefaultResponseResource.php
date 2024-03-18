<?php

namespace App\Http\Resources\Json\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationDefaultResponseResource extends JsonResource
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
            "message" => $this->resource['message'],
            "detail" => $this->resource['detail'],
            "serial" => $this->resource['serial'],
            "transaction_id" => $this->resource['transaction_id'],
            "event_id" => $this->resource['event_id'],
        ];
    }
}
