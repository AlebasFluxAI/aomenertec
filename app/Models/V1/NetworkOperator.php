<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkOperator extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'identification',
        'phone',
        'name',
        'last_name',
        'email',
        'admin_id'
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

    public function getRole()
    {
        return User::TYPE_NETWORK_OPERATOR;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class);
    }

    public function technicians()
    {
        return $this->hasMany(Technician::class);
    }

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }
}
