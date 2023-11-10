<?php

namespace App\Models\Traits;

use App\Models\V1\User;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

trait AddUserFormTrait
{
    public $decodedAddress;
    public $latitude;
    public $longitude;
    public $form_title;
    public $model;
    public $name;
    public $last_name;
    public $email;
    public $address;
    public $message;
    public $phone;
    public $identification;
    public $person_types;
    public $identification_types;
    public $admins;
    public $admin_id;
    public $network_operators;
    public $network_operator_id;

    public function updatedLatitude(Component $component)
    {
        $latlng = "{$component->latitude},{$component->longitude}";
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

        if (!$heremap) {
            return;
        }
        $map = json_decode($heremap ?? '{}');


        try {
            if (!array_key_exists(0, $map->items)) {
                $component->addError('address_error', "No se encuentra informacion de a ubicacion seleccionada");
                return;
            }
            $map = $map->items[0];
            $hereAddress = $map->address;


            $hereMap = json_decode($heremap, true);

            if (array_key_exists('items', $hereMap)) {
                if (count($hereMap['items']) > 0) {
                    if (array_key_exists('address', $hereMap['items'][0])) {
                        $component->decodedAddress = array_key_exists('label', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['label'] : "";
                    }
                }
            }
        } catch (Throwable $e) {
        }
    }

    public function updatedLongitude(Component $component)
    {
        $latlng = "{$component->latitude},{$component->longitude}";
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

        if (!$heremap) {
            return;
        }
        $map = json_decode($heremap ?? '{}');


        try {
            $map = $map->items[0];
            $hereAddress = $map->address;


            $hereMap = json_decode($heremap, true);

            if (array_key_exists('items', $hereMap)) {
                if (count($hereMap['items']) > 0) {
                    if (array_key_exists('address', $hereMap['items'][0])) {
                        $component->decodedAddress = array_key_exists('label', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['label'] : "";
                    }
                }
            }
        } catch (Throwable $e) {
        }
    }

    public function updatedModel(Component $component, $value, $key)
    {
        if ($key == "person_type") {
            $component->identification_types = match ($value) {
                User::PERSON_TYPE_JURIDICAL => [
                    [
                        "key" => User::IDENTIFICATION_TYPE_NIT,
                        "value" => User::IDENTIFICATION_TYPE_NIT
                    ],
                    [
                        "key" => User::IDENTIFICATION_TYPE_OTHER,
                        "value" => "OTRO"
                    ],
                ],
                User::PERSON_TYPE_NATURAL => [
                    [
                        "key" => User::IDENTIFICATION_TYPE_CC,
                        "value" => User::IDENTIFICATION_TYPE_CC,
                    ],
                    [
                        "key" => User::IDENTIFICATION_TYPE_CE,
                        "value" => User::IDENTIFICATION_TYPE_CE,
                    ],
                    [
                        "key" => User::IDENTIFICATION_TYPE_PEP,
                        "value" => User::IDENTIFICATION_TYPE_PEP,
                    ],
                    [
                        "key" => User::IDENTIFICATION_TYPE_PP,
                        "value" => User::IDENTIFICATION_TYPE_PP,
                    ],
                    [
                        "key" => User::IDENTIFICATION_TYPE_NIT,
                        "value" => User::IDENTIFICATION_TYPE_NIT
                    ]
                ],
                default => []
            };
            if ($value == User::PERSON_TYPE_JURIDICAL) {
                $component->model['identification_type'] = User::IDENTIFICATION_TYPE_NIT;
            } else {
                $component->model['identification_type'] = User::IDENTIFICATION_TYPE_CC;
            }
        } elseif ($key == "name" || $key == "last_name") {
            $component->model['billing_name'] = $component->model['name'] . " " . $component->model['last_name'];
        }
    }

    public function updated(Component $component, $propertyName)
    {
        $component->validateOnly($propertyName);
    }

    private function identificationTypes($person_type)
    {
        if ($person_type == User::PERSON_TYPE_NATURAL) {
            return [
                [
                    "key" => User::IDENTIFICATION_TYPE_CC,
                    "value" => User::IDENTIFICATION_TYPE_CC,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_CE,
                    "value" => User::IDENTIFICATION_TYPE_CE,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_PEP,
                    "value" => User::IDENTIFICATION_TYPE_PEP,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_PP,
                    "value" => User::IDENTIFICATION_TYPE_PP,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_NIT,
                    "value" => User::IDENTIFICATION_TYPE_NIT,
                ],
            ];
        } else {
            return [
                [
                    "key" => User::IDENTIFICATION_TYPE_NIT,
                    "value" => User::IDENTIFICATION_TYPE_NIT,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_OTHER,
                    "value" => "OTRO"
                ],

            ];
        }
    }
}
