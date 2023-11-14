<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\Traits\PermissionTrait;
use App\Models\Traits\UserPermissionableTrait;
use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PermissionTrait;
    use AuditableTrait;
    use PaginatorTrait;
    use UserPermissionableTrait;
    use ImageableTrait;


    protected $fillable = [
        'identification',
        'phone',
        'name',
        'last_name',
        'email',
        'user_id',
        'network_operator_id',
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
                        ]

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

    public static function getHome()
    {
        return "livewire.v1.admin.user.supervisor.profile-supervisor";
    }

    public static function getRole()
    {
        return "supervisor";
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function sign()
    {
        return $this->morphOne(Image::class, "imageable");
    }

    public function getPhonePlusIndicativeAttribute()
    {
        return "(" . $this->indicative . ") " . $this->phone;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_supervisors')->withPivot('active');
    }

    public function clientSupervisors()
    {
        return $this->hasMany(ClientSupervisor::class);
    }
}
