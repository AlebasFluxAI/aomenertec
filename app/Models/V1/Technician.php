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

class Technician extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PermissionTrait;
    use AuditableTrait;
    use PaginatorTrait;
    use UserPermissionableTrait;
    use ImageableTrait;


    protected $fillable = ['identification',
        'phone',
        'name',
        'last_name',
        'email',
        'network_operator_id',
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
                    [
                        "title" => "Ordenes de servicio",
                        "route" => "administrar.v1.ordenes_de_servicio.listado",
                        "submenu" => [
                            [
                                "title" => "Ordenes de servicio",
                                "route" => "administrar.v1.ordenes_de_servicio.listado",
                                "submenu" => [

                                ],
                            ],
                        ]

                    ],
                ]
        ];
    }

    public static function getHome()
    {
        return "livewire.v1.admin.user.technician.profile-technician";
    }

    public static function getRole()
    {
        return "technician";
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrderIdScope());
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
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
        return $this->belongsToMany(Client::class, 'client_technicians')->withPivot('active');
    }

    public function clientTechnicians()
    {
        return $this->hasMany(ClientTechnician::class);
    }

    public function technicianEquipmentTypes()
    {
        return $this->hasMany(TechnicianEquipmentType::class);
    }

    public function allEquipments()
    {
        return ($this->getClientEquipments()->merge($this->hasMany(Equipment::class)->get()));
    }

    private function getClientEquipments()
    {
        $equipment = [];
        foreach ($this->clients as $client) {
            $equipment[] = $client->equipments;
        }
        return collect($equipment)->flatten();
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }


}
