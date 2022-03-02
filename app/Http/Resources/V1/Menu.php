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
                    new Menu("Agregar", "administrar.v1.equipos.agregar", []),
                    new Menu("Listar", "administrar.v1.equipos.listado", [],),
                    new Menu("Alertas", null, [
                        new Menu("Agregar", "administrar.v1.equipos.alertas.agregar", []),
                        new Menu("Listar", "administrar.v1.equipos.alertas.listado", [
                        ],),
                    ],)
                ])
            ])
        ];
    }

}

