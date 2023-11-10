<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\User;
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
