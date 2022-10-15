<?php

namespace App\Models\Traits;

use App\Models\V1\Admin;
use App\Models\V1\Image;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

trait UserMenuHomeTrait
{

    public function networkOperator_menu()
    {
        return NetworkOperator::menu();
    }

    public function superAdmin_menu()
    {
        return SuperAdmin::menu();
    }

    public function admin_menu()
    {
        return SuperAdmin::menu();
    }

    public function seller_menu()
    {
        return Seller::menu();
    }

    public function supervisor_menu()
    {
        return Supervisor::menu();
    }

    public function support_menu()
    {
        return Support::menu();
    }

    public function technician_menu()
    {
        return Technician::menu();
    }


    public function networkOperator_home()
    {
        return NetworkOperator::getHome();
    }

    public function superAdmin_home()
    {
        return SuperAdmin::getHome();
    }

    public function admin_home()
    {
        return Admin::getHome();
    }

    public function seller_home()
    {
        return Seller::getHome();
    }

    public function supervisor_home()
    {
        return Supervisor::getHome();
    }

    public function support_home()
    {
        return Support::getHome();
    }

    public function technician_home()
    {
        return Technician::getHome();
    }
}
