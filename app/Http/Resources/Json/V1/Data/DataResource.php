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

        $raw_data = (array) json_decode($this->raw_json);
        unset($raw_data['network_operator_id'], $raw_data['latitude'], $raw_data['longitude'], $raw_data['flags'], $raw_data['timestamp']);
        $this->raw_json = (object) $raw_data;
        return [
            "data" => $this->raw_json,
            "date" => $date->format('Y-m-d'),
            "hour" => $date->format('H:i:s'),
        ];
    }
}
