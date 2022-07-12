<?php

namespace App\Models\V1;

use App\Models\Traits\PermissionTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuperAdmin extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PermissionTrait;

    protected $fillable = ['identification',
        'phone',
        'name',
        'last_name',
        'email',
        'user_id'];

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
                                "title" => "Super administradores",
                                "route" => "administrar.v1.usuarios.superadmin.listado",
                                "submenu" => [
                                    [
                                        "title" => "Usuario sporte",
                                        "route" => "administrar.v1.usuarios.soporte.listado",
                                        "submenu" => []
                                    ],
                                ]
                            ],
                            ["title" => "Administradores",
                                "route" => "administrar.v1.usuarios.admin.listado",
                                "submenu" => []
                            ],
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
                            [
                                "title" => "Tipos",
                                "route" => "administrar.v1.equipos.tipos.listado",
                                "submenu" => []
                            ],
                        ]
                    ],
                ]
        ];
    }

    public static function getHome()
    {
        return "livewire.v1.admin.user.super.profile-super-admin";
    }


    public static function getRole()
    {
        return "super_administrator";
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipmentTypes()
    {
        return $this->hasMany(EquipmentType::class);
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
        return AdminEquipmentType::query();
    }

    public function networkOperatorsAsKeyValue()
    {
        return (array_merge(
            [[
                "key" => "Seleccione el operador de red ...",
                "value" => null
            ]],
            (NetworkOperator::get()->map(function ($neworkOperador) {
                return [
                    "key" => $neworkOperador->id . " - " . $neworkOperador->name . " - " . $neworkOperador->identification,
                    "value" => $neworkOperador->id,
                ];
            }))->toArray()
        ));
    }

    public function equipments()
    {
        return Equipment::query();
    }
}
