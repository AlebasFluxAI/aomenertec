<?php

namespace App\Models\V1;

use App\Http\Resources\V1\PermissionInterface;
use App\Models\Traits\AuditableTrait;
use App\Models\Traits\AvailableChannelTrait;
use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\Traits\PermissionTrait;
use App\Models\Traits\UserPermissionableTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;

class Admin extends Model
{
    use HasFactory;
    use ImageableTrait;
    use HasPermissions;
    use SoftDeletes;
    use AuditableTrait;
    use AvailableChannelTrait;
    use UserPermissionableTrait;
    use PaginatorTrait;


    use PermissionTrait;

    protected $fillable = [
        "user_id",
        'identification',
        'phone',
        'address',
        'billing_address',
        'billing_name',
        'person_type',
        'identification_type',
        'name',
        'last_name',
        'email',
        'css_file',
        "latitude",
        "longitude",
        "address_details",
        "postal_code",
        "here_maps",
        "country",
        "city",
        "state",
        "indicative",
        "invoicing_day",
        "annually_client_cost",
        "annually_client_invoicing_month",
    ];

    public static function getRole()
    {
        return "administrator";
    }

    public static function menu()
    {
        return [
            "title" => "base",
            "route" => "/",
            "submenu" =>
                [
                    [
                        "title" => "Usuarios",
                        "route" => null,
                        "submenu" => [
                            ["title" => "Operadores de red",
                                "route" => "administrar.v1.usuarios.operadores.listado",
                                "submenu" => [
                                    [
                                        "title" => "Operadores de red",
                                        "route" => "administrar.v1.usuarios.operadores.listado",
                                        "submenu" => []
                                    ],
                                    [
                                        "title" => "Vendedores",
                                        "route" => "administrar.v1.usuarios.vendedores.listado",
                                        "submenu" => []
                                    ],
                                    [
                                        "title" => "Supervisores",
                                        "route" => "administrar.v1.usuarios.supervisores.listado",
                                        "submenu" => []
                                    ],
                                    [
                                        "title" => "Tecnicos",
                                        "route" => "administrar.v1.usuarios.tecnicos.listado",
                                        "submenu" => []
                                    ]
                                ]
                            ],


                        ],
                    ],
                    [
                        "title" => "Clientes",
                        "route" => null,
                        "submenu" => [
                            [
                                "title" => "Clientes",
                                "route" => "v1.admin.client.list.client",
                                "submenu" => [

                                ]
                            ],
                            [
                                "title" => "Clientes desactivados",
                                "route" => "v1.admin.client-disabled.list.client",
                                "submenu" => [

                                ],
                            ],

                        ]

                    ],
                    [
                        "title" => "Equipos",
                        "route" => null,
                        "submenu" => [
                            [
                                "title" => "Equipos",
                                "route" => "administrar.v1.equipos.listado",
                                "submenu" => [],
                            ],

                        ]
                    ],
                    [
                        "title" => "PQRS",
                        "route" => "administrar.v1.peticiones.listado",
                        "submenu" => [
                            [
                                "title" => "PQRS",
                                "route" => "administrar.v1.peticiones.listado",
                                "submenu" => [

                                ]
                            ]
                        ]

                    ],
                    [
                        "title" => "Ordenes de servicio",
                        "route" => "administrar.v1.ordenes_de_servicio.listado",
                        "submenu" => [
                            [
                                "title" => "Ordenes de servicio",
                                "route" => "administrar.v1.ordenes_de_servicio.listado",
                                "submenu" => [

                                ],
                            ],
                        ]

                    ],
                    [
                        "title" => "Facturacion",
                        "route" => "administrar.v1.facturacion.facturas.listado",
                        "submenu" => [
                            [
                                "title" => "Facturas",
                                "route" => "administrar.v1.facturacion.facturas.listado",
                                "submenu" => [

                                ],
                            ],
                            [
                                "title" => "Generar reporte",
                                "route" => "administrar.v1.facturacion.facturas.reportes",
                                "submenu" => [

                                ],
                            ],
                        ]

                    ],
                ]
        ];
    }

    public static function getHome()
    {
        return "livewire.v1.admin.user.admin.profile-admin";
    }

    public static function styles()
    {
        return [
            [
                "key" => "Azul - verde - Encabezado negro",
                "value" => "blue_green_black_header"
            ],
            [
                "key" => "Azul - verde - Encabezado blanco",
                "value" => "blue_green_white_header"
            ],
            [
                "key" => "Azul - rojo",
                "value" => "blue_red"
            ],
            [
                "key" => "Azul - rojo - Encabezado negro",
                "value" => "blue_red_black_header"
            ],
            [
                "key" => "Coenergia",
                "value" => "ecoenergia"
            ],
            [
                "key" => "Vaupes",
                "value" => "vaupes"
            ],
            [
                "key" => "Gris - negro",
                "value" => "gray_black"
            ],
            [
                "key" => "Gris - azul",
                "value" => "gray_blue"
            ],
            [
                "key" => "Gris - azul - Encabezado negro",
                "value" => "gray_blue_black_header"
            ],
            [
                "key" => "Gris - azul - Encabezado blanco",
                "value" => "gray_blue_white_header"
            ],
            [
                "key" => "Verde - naranja",
                "value" => "green_orange"
            ],
            [
                "key" => "Verde - naranja - Encabezado blanco",
                "value" => "green_orange_white_header"
            ],
            [
                "key" => "Verde - naranja - Encabezado negro",
                "value" => "green_orange_black_header"
            ],
            [
                "key" => "Cafe - naranja",
                "value" => "orange_brown"
            ],
            [
                "key" => "Cafe - naranja - Encabezado blanco",
                "value" => "orange_brown_white_header"
            ],
            [
                "key" => "Cafe - naranja - Encabezado negro",
                "value" => "orange_brown_black_header"
            ],
            [
                "key" => "Por defecto",
                "value" => "style"
            ],


        ];
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function getCurrentEnabledClients()
    {

        return Client::whereIn('network_operator_id', $this->networkOperators()->pluck('id'))
            ->orWhere("admin_id", $this->id);
    }

    public function networkOperators()
    {
        return $this->hasMany(NetworkOperator::class);
    }

    public function getPhonePlusIndicativeAttribute()
    {
        return "(" . $this->indicative . ") " . $this->phone;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function icon()
    {
        return $this->morphOne(Image::class, "imageable") ?? new Image(["url" => "https://enertedevops.s3.us-east-2.amazonaws.com/images/logotipo-enerteclatam.png"]);
    }

    public function getCssFileNameAttribute()
    {
        return match ($this->css_file) {
            "" => "",
            "blue_green_black_header" => "Azul - verde - Encabezado negro",
            "blue_green_white_header" => "Azul - verde - Encabezado blanco",
            "blue_red" => "Azul - rojo",
            "blue_red_black_header" => "Azul - rojo - Encabezado negro",
            "ecoenergia" => "Coenergia",
            "vaupes" => "Vaupes",
            "gray_black" => "Gris - negro",
            "gray_blue" => "Gris - azul",
            "gray_blue_black_header" => "Gris - azul - Encabezado negro",
            "gray_blue_white_header" => "Gris - azul - Encabezado blanco",
            "green_orange" => "Verde - naranja",
            "green_orange_white_header" => "Verde - naranja - Encabezado blanco",
            "green_orange_black_header" => "Verde - naranja - Encabezado negro",
            "orange_brown" => "Cafe - naranja",
            "orange_brown_white_header" => "Cafe - naranja - Encabezado blanco",
            "orange_brown_black_header" => "Cafe - naranja - Encabezado negro",
            default => "Por defecto"
        };
    }

    public function getClientsAttribute()
    {
        return Client::whereIn("network_operator_id", $this->networkOperators()->pluck("id"))->get();
    }

    public function adminEquipmentToNetworkOperatorsAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el tipo de equipo ...",
                "value" => null
            ]],
            ($this->equipments()
                ->whereNull("network_operator_id")
                ->with("equipmentType")->get()->map(function ($equipment) {
                    return [
                        "key" => $equipment->id . "- " . $equipment->equipmentType->type . "- " . $equipment->serial,
                        "value" => $equipment->id,
                    ];
                }))->toArray()
        ));
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }

    public function equipmentTypesAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el tipo de equipo ...",
                "value" => null
            ]],
            ($this->adminEquipmentTypes()->with("equipmentType")->get()->map(function ($equipmentType) {
                return [
                    "key" => ($equipmentType->equipmentType ? $equipmentType->equipmentType->id : "") . "- "
                        . ucfirst(strtolower(($equipmentType->equipmentType ? $equipmentType->equipmentType->type : ""))),
                    "value" => ($equipmentType->equipmentType ? $equipmentType->equipmentType->id : ""),
                ];
            }))->toArray()
        ));
    }

    public function adminEquipmentTypes()
    {
        return $this->hasMany(AdminEquipmentType::class);
    }

    public function clientTypesAsKeyValue()
    {
        return
            ($this->adminClientTypes()->with("clientType")->get()->map(function ($clientType) {
                return [
                    "key" => ($clientType->clientType ? $clientType->clientType->id : "") . "- "
                        . ucfirst(strtolower(($clientType->clientType ? $clientType->clientType->type : ""))),
                    "value" => ($clientType->clientType ? $clientType->clientType->id : ""),
                ];
            }))->toArray();
    }

    public function adminClientTypes()
    {
        return $this->hasMany(AdminClientType::class);
    }

    public function priceAdmin()
    {
        return $this->hasMany(AdminPrice::class)->orderBy('client_type_id');
    }

    public function configAdmin()
    {
        return $this->hasOne(AdminConfiguration::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function networkOperatorsAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el operador de red ...",
                "value" => null
            ]],
            ($this->networkOperators()->get()->map(function ($neworkOperador) {
                return [
                    "key" => $neworkOperador->id . " - " . $neworkOperador->name . " - " . $neworkOperador->identification,
                    "value" => $neworkOperador->id,
                ];
            }))->toArray()
        ));
    }

    public function adminEquipmentsAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el tipo de equipo ...",
                "value" => null
            ]],
            ($this->equipments()->with("equipmentType")->get()->map(function ($equipment) {
                return [
                    "key" => $equipment->id . "- " . $equipment->equipmentType->type . "- " . $equipment->serial,
                    "value" => $equipment->id,
                ];
            }))->toArray()
        ));
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
