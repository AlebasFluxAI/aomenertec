<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuperAdmin extends Model
{
    use HasFactory;
    use SoftDeletes;

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
                            [
                                "title" => "Alertas",
                                "route" => "administrar.v1.equipos.alertas.listado",
                                "submenu" => [
                                    [
                                        "title" => "Alertas",
                                        "route" => "administrar.v1.equipos.alertas.listado",
                                        "submenu" => []
                                    ],
                                    [
                                        "title" => "Tipos de alerta",
                                        "route" => "administrar.v1.equipos.alertas.tipos.listado",
                                        "submenu" => [

                                        ]
                                    ]
                                ],
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
