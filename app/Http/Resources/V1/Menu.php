<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Menu extends Singleton
{
    public $title;
    public $route;
    public $menus;

    public function __construct($title, $route, $menus)
    {
        $this->title = $title;
        $this->route = $route;
        $this->menus = $menus;
    }


    public static function getMenuV3()
    {
        if (Auth::user() == null) {
            return [];
        }

        if (Request::session()->get(User::SESSION_MULTI_ROLE) and !Request::session()->exists(User::SESSION_ROLE_SELECTED)) {
            return [];
        }

        $userRole = Request::session()->get(User::SESSION_ROLE_SELECTED);
        
        return (new User)->{$userRole . "_menu"}();
    }


    public static function getHome()
    {
        if (Auth::user() == null) {
            return [];
        }
        $userRole = Request::session()->get(User::SESSION_ROLE_SELECTED);
        return (new User)->{$userRole . "_home"}();
    }

    public static function getUserModel()
    {
        return User::getUserModel();
    }
}
