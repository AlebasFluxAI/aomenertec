<?php

namespace Database\Seeders;

use App\Models\V1\Admin;
use Illuminate\Database\Seeder;

class EquipmentAssignationPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Admin::get() as $admin) {
            $admin->givePermissionTo("set_equipment_type_to_network_operator");
        }
    }
}
