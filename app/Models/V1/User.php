<?php

namespace App\Models\V1;

use App\Models\Traits\AuditableTrait;
use App\Models\Traits\PaginatorTrait;
use App\Models\Traits\UserMenuHomeTrait;
use App\Models\V1\Api\ApiKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;
    use AuditableTrait;
    use UserMenuHomeTrait;
    use PaginatorTrait;


    public const TYPE_SUPER_ADMIN = "super_administrator";
    public const TYPE_ADMIN = "administrator";
    public const TYPE_SUPPORT = "support";
    public const TYPE_NETWORK_OPERATOR = "network_operator";
    public const TYPE_SELLER = "seller";
    public const TYPE_TECHNICIAN = "technician";
    public const TYPE_SUPERVISOR = "supervisor";


    public const PERSON_TYPE_NATURAL = "natural";
    public const PERSON_TYPE_JURIDICAL = "juridical";

    public const IDENTIFICATION_TYPE_CC = 'CC';
    public const IDENTIFICATION_TYPE_CE = 'CE';
    public const IDENTIFICATION_TYPE_PEP = 'PEP';
    public const IDENTIFICATION_TYPE_PP = 'PP';
    public const IDENTIFICATION_TYPE_NIT = 'NIT';
    public const IDENTIFICATION_TYPE_OTHER = 'OTHER';

    public const SESSION_ROLE_SELECTED = "role_selected";
    public const SESSION_SINGLE_ROLE = "single_role";
    public const SESSION_MULTI_ROLE = "multio_role";
    public const SESSION_USER_AUTH = "user_auth";
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
        'type',
        "indicative"
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

    public static function getUserModel()
    {
        $user = Request::session()->get(User::SESSION_USER_AUTH) ?? Auth::user();
        if (!Request::session()->get(User::SESSION_USER_AUTH)) {
            Request::session()->put(User::SESSION_USER_AUTH, $user);
        }
        $userRole = Request::session()->get(User::SESSION_ROLE_SELECTED) ?? User::getUserRoles()[0]["rol"];
        return $user->{$userRole};

    }
    public static function getUserModelApi(ApiKey $key)
    {
        $user = $key->user;
        $userRole = User::getUserRolesApi($user)[0]["rol"];
        return $user->{$userRole};

    }
    public static function getUserRolesApi(User $user)
    {

        $roles = [];

        foreach (["superAdmin",
                     "admin",
                     "networkOperator",
                     "seller",
                     "supervisor",
                     "support",
                     "technician"] as $role) {
            if ($user->{$role}) {
                $roles[] = [
                    "rol" => $role,
                    "name" => __("roles." . $role),
                    "icon" => __("roles." . $role . "_icon")
                ];

            }
        }

        return $roles;

    }

    public static function getUserRoles()
    {
        $user = Auth::user();
        $roles = [];

        foreach (["superAdmin",
                     "admin",
                     "networkOperator",
                     "seller",
                     "supervisor",
                     "support",
                     "technician"] as $role) {
            if ($user->{$role}) {
                $roles[] = [
                    "rol" => $role,
                    "name" => __("roles." . $role),
                    "icon" => __("roles." . $role . "_icon")
                ];

            }
        }

        return $roles;

    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getPhonePlusIndicativeAttribute()
    {
        return "(" . $this->indicative . ") " . $this->phone;
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function otpUsers()
    {
        return $this->hasMany(OtpUser::class);
    }

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

    public function getUserType()
    {
        return $this->roles->first()->name;
    }

    public function getUserRole()
    {
        return $this->roles->first()->display_name;
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
        return "Enertec";
    }

    public function pqrUsers()
    {
        return $this->hasMany(PqrUser::class);
    }


}
