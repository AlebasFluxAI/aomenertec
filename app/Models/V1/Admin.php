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
            "green_orange" => "Verde - Naranja",
            "green_orange_black_header" => "Verde - Naranja | Header negro",
            "orange_brown" => "Naranja - Cafe",
            "orange_brown_black_header" => "Naranja - Cafe | Header negro",
            "style" => "Negro - Naranja",
            "black_white" => "Gris - negro",
            "blue_red" => "Azul - Rojo",
            "blue_red_black_header" => "Azul - Rojo | Header negro",
            "purple_pink" => "Morado - Rosa",
            "purple_pink_black_header" => "Morado - Rosa | Header negro",
            "ecoenergia" => "Coenergia",
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
