<?php

namespace Database\Seeders;

use App\Models\V1\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ModelHasRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller = User::where("name", "like", '%' . "Vendedor" . "%")->get();
        $technician = User::where("name", "like", '%' . "Tecnico" . "%")->get();
        $network_operator = User::where("name", "like", '%' . "Prestador" . "%")->get();
        $super_admin = User::find(1);
        $admin = User::find(2);
        $support = User::find(3);
        foreach ($seller as $item) {
            $item->assignRole('seller');
        }
        foreach ($technician as $item) {
            $item->assignRole('technician');
        }
        foreach ($network_operator as $item) {
            $item->assignRole('network_operator');
        }
        $super_admin->assignRole('super_administrator');
        $admin->assignRole('administrator');
        $support->assignRole('support');
    }
}
