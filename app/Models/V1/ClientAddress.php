<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class ClientAddress extends Model
{
    use HasFactory;


    const STATUS_ENABLED = "enabled";
    const STATUS_DISABLED = "disabled";

    protected $fillable = [
        "latitude",
        "longitude",
        "address",
        "country",
        "city",
        "state",
        "client_id",
        "here_maps",
        "postal_code",
        "status"
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function setHereMapJson()
    {

        if (!$this->latitude or !$this->longitude) {
            return;
        }

        $latlng = "{$this->latitude},{$this->longitude}";
        $response = Http::get('https://revgeocode.search.hereapi.com/v1/revgeocode', [
            'at' => $latlng,
            'apiKey' => config("here.apiKey"),
        ]);

        if (200 == $response->status()) {
            $body = $response->json();

            if (array_key_exists('items', $body)) {
                $this->here_maps = json_encode($body);
            }
        }

    }
}
