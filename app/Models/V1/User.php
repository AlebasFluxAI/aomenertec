<?php

namespace App\Models\V1;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    public const TYPE_SUPER_ADMIN = "super_administrator";
    public const TYPE_ADMIN = "administrator";
    public const TYPE_SUPPORT = "support";
    public const TYPE_NETWORK_OPERATOR = "network_operator";
    public const TYPE_SELLER = "seller";
    public const TYPE_TECHNICIAN = "technician";
    public const TYPE_SUPERVISOR = "supervisor";
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'identification',
        'phone',
        'name',
        'last_name',
        'email',
        'password',
        'enabled',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function networkOperator()
    {
        return $this->hasOne(NetworkOperator::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }


    public function technician()
    {
        return $this->hasOne(Technician::class);
    }


    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
    }

    public function support()
    {
        return $this->hasOne(Support::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function superAdmin()
    {
        return $this->hasOne(SuperAdmin::class);
    }


    public function pqrs()
    {
        return $this->hasMany(Pqr::class);
    }

    public function setDefaultPassword()
    {
        $this->password = bcrypt($this->identification);
    }

    public function getName()
    {
        return "Nombre quemado";
    }

    public function getUserType()
    {
        return $this->roles->first()->name;
    }

    public function getAdmin()
    {
        if ($superAdmin = $this->superAdmin) {
            return $superAdmin;
        }
        if ($admin = $this->admin) {
            return $admin;
        }
        if ($networkOperator = $this->networkOperator) {
            return $networkOperator->admin;
        }

        if ($seller = $this->seller) {
            return $seller->networkOperator->admin;
        }
        if ($supervisor = $this->supervisor) {
            return $supervisor->networkOperator->admin;
        }
        if ($technician = $this->technician) {
            return $technician->networkOperator->admin;
        }
        return "https://aom.enerteclatam.com/images/logo-horizontal.svg";
    }

    public function getPasswordRestoreUrl()
    {
        return "Holi";
    }
}
