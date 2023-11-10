<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\Traits\PermissionTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PermissionTrait;
    use AuditableTrait;
    use PaginatorTrait;


    protected $fillable = [
        'identification',
        'phone',
        'name',
        'last_name',
        'email',
        'user_id',
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
        "pqr_available",
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
                        "title" => "Clientes",
                        "route" => null,
                        "submenu" => [
                            [
                                "title" => "Clientes",
                                "route" => "v1.admin.client.list.client",
                                "submenu" => [

                                ]
                            ]
                        ],


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
                            ],
                            [
                                "title" => "Cola ordenes de pqr",
                                "route" => "administrar.v1.usuarios.soporte.cola.pqr",
                                "submenu" => [

                                ],
                            ],
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
                        "title" => "Ordenes de servicio",
                        "route" => "administrar.v1.ordenes_de_servicio.listado",
                        "submenu" => [
                            [
                                "title" => "Ordenes de servicio tomadas",
                                "route" => "administrar.v1.ordenes_de_servicio.listado",
                                "submenu" => [

                                ],
                            ],
                            [
                                "title" => "Cola ordenes de servicio",
                                "route" => "administrar.v1.usuarios.soporte.cola.ordenes_de_trabajo",
                                "submenu" => [

                                ],
                            ],

                        ]

                    ],
                ]
        ];
    }

    public static function getRole()
    {
        return User::TYPE_SUPPORT;
    }

    public static function getHome()
    {
        return "livewire.v1.admin.user.support.profile-support";
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function getPhonePlusIndicativeAttribute()
    {
        return "(" . $this->indicative . ") " . $this->phone;
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }

    public function clientSupports()
    {
        return $this->hasMany(ClientSupport::class);
    }

    public function blinkPqrAvailability()
    {
        $this->update([
            "pqr_available" => $this->pqr_available ? false : true,
        ]);
    }
}
