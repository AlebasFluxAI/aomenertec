<?php

namespace App\Http\Resources\Json\V1\Data;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $date = Carbon::create($this->source_timestamp);
        unset($this->raw_json->network_operator_id, $this->raw_json->latitude, $this->raw_json->longitude, $this->raw_json->flags, $this->raw_json->timestamp);
        return [
            "data" => json_decode($this->raw_json),
            "date" => $date->format('Y-m-d'),
            "hour" => $date->format('H'),
        ];
    }
}
