<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;

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

    static function getMenu()
    {
        return [new Menu(
            "base",
            "/",
            [

                new Menu("Usuarios", null, [
                    new Menu("Agregar", "administrar.v1.usuarios.agregar", []),
                ]),

                new Menu("Equipos", null, [
                    new Menu("Equipos", "administrar.v1.equipos.listado", [],),
                    new Menu("Alertas", null, [
                        new Menu("Alertas", "administrar.v1.equipos.alertas.listado", []),
                    ],),
                ],)
            ])
        ];
    }


    static function getMenuV2()
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
                                "title" => "Agregar",
                                "route" => "administrar.v1.usuarios.agregar",
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

}

