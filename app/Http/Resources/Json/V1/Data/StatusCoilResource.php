<?php

namespace App\Http\Resources\V1\Data;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusCoilResource extends JsonResource
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

                "serial" => $this->resource['serial'],
                "active" => $this->resource['activo'],
                "message" => $this->resource['message'],
                "detail" => $this->resource['detail']

        ];
    }
}
