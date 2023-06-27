<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\Traits\PermissionTrait;
use App\Models\Traits\UserPermissionableTrait;
use App\Scope\ClientEnabledScope;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class NetworkOperator extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PermissionTrait;
    use UserPermissionableTrait;
    use AuditableTrait;
    use PaginatorTrait;

    public const GENERATION_FEE = "generation";
    public const TRANSMISSION_FEE = "transmission";
    public const DISTRIBUTION_FEE = "distribution";
    public const COMMERCIALIZATION_FEE = "commercialization";
    public const LOST_FEE = "lost";
    public const RESTRICTIONS_FEE = "restriction";
    public const UNIT_COST_FEE = "unit_cost";
    public const TOTAL_FEE = "total_fee";

    public const DISCOUNT_CONCEPT = "discount";
    public const CONTRIBUTION_CONCEPT = "contribution";
    public const TAX_CONCEPT = "tax";
    public const OPTIONAL_FEE = "optional_fee";

    protected $fillable = [
        'user_id',
        'identification',
        'phone',
        'name',
        'last_name',
        'email',
        'admin_id',
        "address",
        'billing_address',
        'billing_name',
        'person_type',
        'identification_type',
        "latitude",
        "longitude",
        "address_details",
        "postal_code",
        "here_maps",
        "country",
        "city",
        "state",
        "indicative"
    ];

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
                                "title" => "Técnicos",
                                "route" => "administrar.v1.usuarios.tecnicos.listado",
                                "submenu" => []
                            ]

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
                            ]
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
                        "title" => "Precios",
                        "route" => "administrar.v1.usuarios.operadores.modulo_precios",
                        "submenu" => [
                            [
                                "title" => "Modulo de precios",
                                "route" => "administrar.v1.usuarios.operadores.modulo_precios",
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

    public static function getOtherConcepts()
    {
        return [
            self::DISCOUNT_CONCEPT,
            self::CONTRIBUTION_CONCEPT,
            self::TAX_CONCEPT,
        ];
    }

    public function getPhonePlusIndicativeAttribute()
    {
        return "(" . $this->indicative . ") " . $this->phone;
    }

    public function zniFees()
    {
        return $this->hasMany(ZniLevelFee::class);
    }

    public function sinFees()
    {
        return $this->hasMany(SinLevelFee::class);
    }


    public function sinOtherFees()
    {
        return $this->hasMany(SinOtherFee::class);
    }

    public function zniOtherFees()
    {
        return $this->hasMany(ZniOtherFee::class);
    }

    public static function priceOtionalType()
    {
        return [
            self::OPTIONAL_FEE,
        ];
    }

    public static function priceType()
    {
        return [
            self::GENERATION_FEE,
            self::TRANSMISSION_FEE,
            self::DISTRIBUTION_FEE,
            self::COMMERCIALIZATION_FEE,
            self::LOST_FEE,
            self::RESTRICTIONS_FEE,
            self::UNIT_COST_FEE,
            self::TOTAL_FEE
        ];
    }

    public static function getLevelTension()
    {
        return [
            ZniLevelFee::LEVEL_1_A,
            ZniLevelFee::LEVEL_1_B,
            ZniLevelFee::LEVEL_1_C,
            ZniLevelFee::LEVEL_2,
            ZniLevelFee::LEVEL_3,
        ];
    }

    public function getClientTypeForPrice()
    {
        return (array_map(function ($key) {
            return [
                "title" => $key
            ];
        }, ClientType::whereIn("type", [
            ClientType::SIN_CONVENTIONAL,
            ClientType::ZIN_CONVENTIONAL,
        ])->whereIn("id", $this->clients()->pluck("client_type_id")->unique())->pluck("type")->toArray()));

    }

    public function getTabContentForPrice()
    {
        return (array_map(function ($key) {
            return [
                "view_name" => "livewire.v1.admin.user.network-operator.price-calculator.calculator",
                "view_values" => [
                    "client_type" => $key
                ],

            ];
        }, ClientType::whereIn("type", [
            ClientType::SIN_CONVENTIONAL,
            ClientType::ZIN_CONVENTIONAL,
        ])->whereIn("id", $this->clients()->pluck("client_type_id")->unique())->pluck("type")->toArray()));

    }

    public function photovoltaicPrice()
    {
        return $this->hasMany(PhotovoltaicPrice::class);
    }

    public static function getHome()
    {
        return "livewire.v1.admin.user.network-operator.profile-network-operator";
    }

    public static function getRole()
    {
        return User::TYPE_NETWORK_OPERATOR;
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function techniciansAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el técnico...",
                "value" => null
            ]],
            ($this->technicians()->get()->map(function ($technician) {
                return [
                    "key" => $technician->id . " - " . $technician->name . " - " . $technician->identification,
                    "value" => $technician->id
                ];
            }))->toArray()
        ));
    }

    public function technicians()
    {
        return $this->hasMany(Technician::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function allClients()
    {
        return $this->hasMany(Client::class)->withoutGlobalScope(ClientEnabledScope::class);
    }


    public function sellers()
    {
        return $this->hasMany(Seller::class);
    }

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }

    public function networkOperatorEquipmentToTechnicianAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el tipo de equipo ...",
                "value" => null
            ]],
            ($this->equipments()
                ->whereNull("technician_id")
                ->with("equipmentType")->get()->map(function ($equipment) {
                    return [
                        "key" => $equipment->id . "- " . $equipment->equipmentType->type . "- " . $equipment->serial,
                        "value" => $equipment->id,
                    ];
                }))->toArray()
        ));
    }

    public function equipmentTypesAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el tipo de equipo ...",
                "value" => null
            ]],
            ($this->admin->adminEquipmentTypes()->with("equipmentType")->get()->map(function ($equipmentType) {
                return [
                    "key" => ($equipmentType->equipmentType ? $equipmentType->equipmentType->id : "") . "- "
                        . ucfirst(strtolower(($equipmentType->equipmentType ? $equipmentType->equipmentType->type : ""))),
                    "value" => ($equipmentType->equipmentType ? $equipmentType->equipmentType->id : ""),
                ];
            }))->toArray()
        ));
    }


    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}
