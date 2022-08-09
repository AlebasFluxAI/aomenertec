<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class Menu extends Singleton
{
    public $title;
    public $route;
    public $menus;

    public function __construct($title, $route, $menus)
    {
        $this->title = $title;
        $this->route = $route;
        $this->menus = $menus;
    }

    public static function getMenu()
    {
        return [new Menu(
            "base",
            "/",
            [

                new Menu("Usuarios", null, [
                    new Menu("Agregar", "administrar.v1.usuarios.agregar", [
                        new Menu("Super administradored", null, [
                            new Menu("Agregar", "administrar.v1.usuarios.superadmin.listado", []),
                        ]),
                    ]),
                ]),

                new Menu("Equipos", null, [
                    new Menu("Equipos", "administrar.v1.equipos.listado", [],),
                    new Menu("Alertas", null, [
                        new Menu("Alertas", "administrar.v1.equipos.alertas.listado", []),
                    ],),
                ],)
            ]
        )
        ];
    }


    public static function getMenuV2()
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
                                        "title" => "Usuario soporte",
                                        "route" => "administrar.v1.usuarios.admin.listado",
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
                            ]

                        ],
                    ],
                    [
                        "title" => "Clientes",
                        "route" => null,
                        "submenu" => [
                            [
                                "title" => "Agregar",
                                "route" => "v1.admin.client.add.client",
                                "submenu" => []
                            ]
                        ],
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


    public static function getMenuV3()
    {
        if (Auth::user() == null) {
            return [];
        }
        $userRole = Auth::user()->roles->first()->name;


        $menu = [];
        switch ($userRole) {
            case User::TYPE_NETWORK_OPERATOR:
                $menu = NetworkOperator::menu();
                break;
            case User::TYPE_ADMIN:
                $menu = Admin::menu();
                break;
            case User::TYPE_SUPER_ADMIN:
                $menu = SuperAdmin::menu();
                break;
            case User::TYPE_SELLER:
                $menu = Seller::menu();
                break;
            case User::TYPE_SUPERVISOR:
                $menu = Supervisor::menu();
                break;
            case User::TYPE_TECHNICIAN:
                $menu = Technician::menu();
                break;
            case User::TYPE_SUPPORT:
                $menu = Support::menu();
                break;
            default:
                $menu = [];
        }
        return $menu;
    }


    public static function getHome()
    {
        if (Auth::user() == null) {
            return [];
        }

        $userRole = Auth::user()->roles->first()->name;


        $home = match ($userRole) {
            User::TYPE_SUPER_ADMIN => SuperAdmin::getHome(),
            User::TYPE_NETWORK_OPERATOR => NetworkOperator::getHome(),
            User::TYPE_ADMIN => Admin::getHome(),
            User::TYPE_SELLER => Seller::getHome(),
            User::TYPE_SUPERVISOR => Supervisor::getHome(),
            User::TYPE_SUPPORT => Support::getHome(),
            User::TYPE_TECHNICIAN => Technician::getHome(),
            default => [],
        };
        return $home;
    }

    public static function getUserModel()
    {
        return User::getUserModel();
    }
}
