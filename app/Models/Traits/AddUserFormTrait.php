<?php

namespace App\Models\Traits;

use App\Models\V1\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

trait AddUserFormTrait
{
    public $decodedAddress;
    public $identification;
    public $latitude = 4.134750;
    public $longitude = -73.637094;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $form_title;

    public function updatedLatitude()
    {
        $latlng = "{$this->latitude},{$this->longitude}";
        $heremap = null;
        $response = Http::get('https://revgeocode.search.hereapi.com/v1/revgeocode', [
            'at' => $latlng,
            'apiKey' => config("here.apiKey"),
        ]);

        if (200 == $response->status()) {
            $body = $response->json();

            if (array_key_exists('items', $body)) {
                $heremap = json_encode($body);
            }
        }


        $map = json_decode($heremap ?? '{}');


        try {
            $map = $map->items[0];
            $hereAddress = $map->address;


            $hereMap = json_decode($heremap, true);

            if (array_key_exists('items', $hereMap)) {
                if (count($hereMap['items']) > 0) {
                    if (array_key_exists('address', $hereMap['items'][0])) {
                        $this->decodedAddress = array_key_exists('label', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['label'] : "";

                    }
                }
            }
        } catch (Throwable $e) {
        }
    }
}
