<?php

namespace App\Http\Resources\Json\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
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
            "error" => [
                "code" => $this->resource['code'],
                "message" => $this->resource['message'],
                "details" => $this->resource['details']
            ]
        ];
    }
}
