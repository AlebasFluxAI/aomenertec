<?php

namespace App\Models\V1;

use App\Scope\OrderIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'identification',
        'phone',
        'name',
        'last_name',
        'email',
        'user_id',
        'network_operator_id',
        "address",
        "latitude",
        "longitude",
        "address_details",
        "postal_code",
        "here_maps",
        "country",
        "city",
        "state",
    ];

    public static function getRole()
    {
        return User::TYPE_SELLER;
    }

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
        return "livewire.v1.admin.user.seller.profile-seller";
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

    public function clientSellers()
    {
        return $this->hasMany(ClientSeller::class);
    }
}
