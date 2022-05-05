<?php

namespace App\Models\V1;

use App\Models\Traits\ValidateUserFormTrait;
use App\Models\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Role;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory;
    use ImageableTrait;


    protected $fillable = [
        "user_id",
        'identification',
        'phone',
        'nit',
        'address',
        'name',
        'last_name',
        'email',
        'css_file'
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


        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function icon()
    {
        return $this->morphOne(Image::class, "imageable") ?? new Image(["url" => "https://aom.enerteclatam.com/images/logo-horizontal.svg"]);
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
        };
    }

    public function getClientsAttribute()
    {
        return Client::whereIn("network_operator_id", $this->networkOperators()->pluck("id"))->get();
    }

    public function networkOperators()
    {
        return $this->hasMany(NetworkOperator::class);
    }
}
