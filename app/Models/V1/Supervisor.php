<?php

namespace App\Models\V1;

use App\Models\Traits\PermissionTrait;
use App\Scope\OrderIdScope;
use Database\Seeders\ClientsTableSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PermissionTrait;

    protected $fillable = ['identification',
        'phone',
        'name',
        'last_name',
        'email',
        'user_id',
        'network_operator_id'
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
