<?php

use App\Http\Controllers\testFile;
use App\Http\Controllers\V1\HomeController;
use App\Http\Livewire;
use App\Http\Livewire\Index;
use App\Http\Livewire\V1\Admin\User\AddUser;
use App\Http\Livewire\V1\Admin\User\EditUser;
use App\Http\Livewire\V1\Admin\Client\AddClient;
use App\Http\Livewire\V1\Admin\Client\EditClient;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|---------------------------------------ß-----------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('', [testFile::class, 'upload']);

Route::get('/', function () {
    return view('auth.v1.login');
});
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::prefix("v1")->group(function () {
    Route::prefix("administrar")->group(function () {
        Route::get('/', Index::class);
        Route::middleware([])->group(function () {
            Route::prefix("usuarios")->group(function () {
                Route::get('agregar', AddUser::class)->name("administrar.v1.usuarios.agregar");
                Route::get('editar', EditUser::class)->name("administrar.v1.usuarios.editar");
                Route::prefix("super_administrador")->group(function () {
                    Route::get('listado', Livewire\V1\Admin\User\SuperAdmin\IndexSuperAdmin::class)->name("administrar.v1.usuarios.superadmin.listado");
                    Route::get('agregar', Livewire\V1\Admin\User\SuperAdmin\AddSuperAdmin::class)->name("administrar.v1.usuarios.superadmin.agregar");
                });

                Route::prefix("administrador")->group(function () {
                    Route::get('listado', Livewire\V1\Admin\User\Admin\IndexAdmin::class)->name("administrar.v1.usuarios.admin.listado");
                    Route::get('agregar', Livewire\V1\Admin\User\Admin\AddAdmin::class)->name("administrar.v1.usuarios.admin.agregar");
                });


                Route::prefix("operador")->group(function () {
                    Route::get('listado', Livewire\V1\Admin\User\NetworkOperator\IndexNetworkOperator::class)->name("administrar.v1.usuarios.operadores_de_red.listado");
                    Route::get('agregar', Livewire\V1\Admin\User\NetworkOperator\AddNetworkOperator::class)->name("administrar.v1.usuarios.operadores_de_red.agregar");
                });


                Route::prefix("vendedor")->group(function () {
                    Route::get('listado', Livewire\V1\Admin\User\Seller\IndexSeller::class)->name("administrar.v1.usuarios.vendedores.listado");
                    Route::get('agregar', Livewire\V1\Admin\User\Seller\AddSeller::class)->name("administrar.v1.usuarios.vendedores.agregar");
                });


                Route::prefix("supervisor")->group(function () {
                    Route::get('listado', Livewire\V1\Admin\User\Supervisor\IndexSupervisor::class)->name("administrar.v1.usuarios.supervisores.listado");
                    Route::get('agregar', Livewire\V1\Admin\User\Supervisor\AddSupervisor::class)->name("administrar.v1.usuarios.supervisores.agregar");
                });

            });
        });
        Route::middleware(['permission:add_client', 'permission:edit_client'])->group(function () {
            Route::prefix("clientes")->group(function () {
                Route::get('agregar', AddClient::class)->name('admin.add-client');
                Route::get('editar', EditClient::class)->name('admin.edit-client');
            });
        });
        Route::prefix("equipos")->group(function () {
            Route::get('agregar', Livewire\V1\Admin\Equipment\AddEquipment::class)->name("administrar.v1.equipos.agregar");
            Route::get('listado', Livewire\V1\Admin\Equipment\IndexEquipment::class)->name("administrar.v1.equipos.listado");
            Route::get('detalle/{equipment}', Livewire\V1\Admin\Equipment\DetailEquipment::class)->name("administrar.v1.equipos.detalle");
            Route::get('editar/{equipment}', Livewire\V1\Admin\Equipment\EditEquipment::class)->name("administrar.v1.equipos.editar");
            Route::prefix("tipos")->group(function () {
                Route::get('agregar', Livewire\V1\Admin\EquipmentType\AddEquipmentType::class)->name("administrar.v1.equipos.tipos.agregar");
                Route::get('listado', Livewire\V1\Admin\EquipmentType\IndexEquipmentType::class)->name("administrar.v1.equipos.tipos.listado");
                Route::get('detalle/{equipmentType}', Livewire\V1\Admin\EquipmentType\DetailEquipmentType::class)->name("administrar.v1.equipos.tipos.detalle");
                Route::get('editar/{equipmentType}', Livewire\V1\Admin\EquipmentType\EditEquipmentType::class)->name("administrar.v1.equipos.tipos.editar");

            });
            Route::prefix("alertas")->group(function () {
                Route::get('agregar', Livewire\V1\Admin\EquipmentAlert\AddEquipmentAlert::class)->name("administrar.v1.equipos.alertas.agregar");
                Route::get('listado', Livewire\V1\Admin\EquipmentAlert\IndexEquipmentAlert::class)->name("administrar.v1.equipos.alertas.listado");
                Route::get('editar/{equipmentAlert}', Livewire\V1\Admin\EquipmentAlert\EditEquipmentAlert::class)->name("administrar.v1.equipos.alertas.editar");
                Route::get('detalle/{equipmentAlert}', Livewire\V1\Admin\EquipmentAlert\DetailEquipmentAlert::class)->name("administrar.v1.equipos.alertas.detalle");
                Route::prefix("tipos")->group(function () {
                    Route::get('agregar', Livewire\V1\Admin\AlertType\AddAlertType::class)->name("administrar.v1.equipos.alertas.tipos.agregar");
                    Route::get('listado', Livewire\V1\Admin\AlertType\IndexAlertType::class)->name("administrar.v1.equipos.alertas.tipos.listado");
                    Route::get('editar/{alertType}', Livewire\V1\Admin\AlertType\EditAlertType::class)->name("administrar.v1.equipos.alertas.tipos.editar");
                    Route::get('detalle/{alertType}', Livewire\V1\Admin\AlertType\DetailAlertType::class)->name("administrar.v1.equipos.alertas.tipos.detalle");
                });
            });
        });
    });
});


Route::post('/broadcasting/autha', function () {
    return true;
});
