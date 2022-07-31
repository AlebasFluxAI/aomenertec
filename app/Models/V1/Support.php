<?php

namespace App\Models\V1;

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
        "pqr_available"
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
                            ]
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
