<?php

namespace App\Models\V1;

use App\Models\Traits\PermissionTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkOperator extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PermissionTrait;

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
                ]
        ];
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
