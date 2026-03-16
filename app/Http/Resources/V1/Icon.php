<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Icon extends Singleton
{
    public static function getIcon()
    {
        try {
            return self::getUserIcon();
        } catch (Throwable $exception) {
            return asset('images/flux-ai-logo-horizontal.png');
        }
    }

    public static function getIconSidebar()
    {
        return asset('images/flux-ai-logo-sidebar.png');
    }

    private static function getUserIcon()
    {
        if ($admin = Auth::user()->getAdmin()) {
            return $admin->icon->url;
        }
        return asset('images/flux-ai-logo-horizontal.png');
    }

    public static function getUserIconUser($user)
    {
        try {
            if ($admin = $user->getAdmin()) {
                return $admin->icon->url;
            }
        } catch (Throwable $e) {
            return asset('images/flux-ai-logo-horizontal.png');
        }
    }
}
