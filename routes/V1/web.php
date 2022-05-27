<?php

use App\Http\Controllers\testFile;
use App\Http\Controllers\V1\HomeController;
use App\Http\Controllers\V1\MailTestController;
use App\Http\Livewire;
use App\Http\Livewire\Index;
use App\Http\Livewire\V1\Admin\User\AddUser;
use App\Http\Livewire\V1\Admin\User\EditUser;
use App\Http\Livewire\V1\Admin\Client\AddClient;
use App\Http\Livewire\V1\Admin\Client\EditClient;
use App\Http\Livewire\V1\Admin\Client\IndexClient;
use App\Http\Livewire\V1\Admin\Client\DetailClient;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    if (Auth::user()) {
        return redirect()->route("administrar.v1.perfil");
    }
    return view('auth.login');
});


Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get("mail/test/user_created", (MailTestController::class) . "@userCreatedNotification");
Route::post("mail/test/whatsapp_created", (MailTestController::class) . "@whatsappNotification");

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::prefix("v1")->group(function () {
        Route::get('/inicio', Livewire\V1\Admin\User\ProfileUser::class)->name("administrar.v1.perfil");
        Route::prefix("administrar")->group(function () {
            Route::middleware([])->group(function () {
                Route::prefix("usuarios")->group(function () {
                    Route::get('agregar', AddUser::class)->name("administrar.v1.usuarios.agregar");
                    Route::get('editar', EditUser::class)->name("administrar.v1.usuarios.editar");

                    Route::prefix("super_administrador")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\SuperAdmin\IndexSuperAdmin::class)->name("administrar.v1.usuarios.superadmin.listado");
                        Route::get('agregar', Livewire\V1\Admin\User\SuperAdmin\AddSuperAdmin::class)->name("administrar.v1.usuarios.superadmin.agregar");
                        Route::get('detalle/{superAdmin}', Livewire\V1\Admin\User\SuperAdmin\DetailsSuperAdmin::class)->name("administrar.v1.usuarios.superadmin.detalles");
                        Route::get('editar/{superAdmin}', Livewire\V1\Admin\User\SuperAdmin\EditSuperAdmin::class)->name("administrar.v1.usuarios.superadmin.editar");
                    });

                    Route::prefix("administrador")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Admin\IndexAdmin::class)->name("administrar.v1.usuarios.admin.listado");
                        Route::get('agregar', Livewire\V1\Admin\User\Admin\AddAdmin::class)->name("administrar.v1.usuarios.admin.agregar");
                        Route::get('editar/{admin}', Livewire\V1\Admin\User\Admin\EditAdmin::class)->name("administrar.v1.usuarios.admin.editar");
                        Route::get('detalle/{admin}', Livewire\V1\Admin\User\Admin\DetailsAdmin::class)->name("administrar.v1.usuarios.admin.detalles");
                        Route::get('agregar_tipos_equipos/{admin}', Livewire\V1\Admin\User\Admin\AddEquipmentTypeAdmin::class)->name("administrar.v1.usuarios.admin.agregar_tipos_equipo");
                        Route::get('agregar_equipos/{admin}', Livewire\V1\Admin\User\Admin\AddEquipmentAdmin::class)->name("administrar.v1.usuarios.admin.agregar_equipos");
                    });


                    Route::prefix("operador")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\NetworkOperator\IndexNetworkOperator::class)->name("administrar.v1.usuarios.operadores.listado");
                        Route::get('agregar', Livewire\V1\Admin\User\NetworkOperator\AddNetworkOperator::class)->name("administrar.v1.usuarios.operadores.agregar");
                        Route::get('editar/{networkOperator}', Livewire\V1\Admin\User\NetworkOperator\EditNetworkOperator::class)->name("administrar.v1.usuarios.operadores.editar");
                        Route::get('detalle/{networkOperator}', Livewire\V1\Admin\User\NetworkOperator\DetailsNetworkOperator::class)->name("administrar.v1.usuarios.operadores.detalles");
                        Route::get('agregar_equipos/{networkOperator}', Livewire\V1\Admin\User\NetworkOperator\AddEquipmentNetworkOperator::class)->name("administrar.v1.usuarios.operadores.agregar_equipos");
                    });


                    Route::prefix("vendedor")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Seller\IndexSeller::class)->name("administrar.v1.usuarios.vendedores.listado");
                        Route::get('agregar', Livewire\V1\Admin\User\Seller\AddSeller::class)->name("administrar.v1.usuarios.vendedores.agregar");
                        Route::get('editar/{seller}', Livewire\V1\Admin\User\Seller\EditSeller::class)->name("administrar.v1.usuarios.vendedores.editar");
                        Route::get('detalle/{seller}', Livewire\V1\Admin\User\Seller\DetailsSeller::class)->name("administrar.v1.usuarios.vendedores.detalles");
                        Route::get('agregar_clientes/{seller}', Livewire\V1\Admin\User\Seller\AddClientSeller::class)->name("administrar.v1.usuarios.vendedores.agregar_clientes");
                    });


                    Route::prefix("supervisor")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Supervisor\IndexSupervisor::class)->name("administrar.v1.usuarios.supervisores.listado");
                        Route::get('agregar', Livewire\V1\Admin\User\Supervisor\AddSupervisor::class)->name("administrar.v1.usuarios.supervisores.agregar");
                        Route::get('detalle/{supervisor}', Livewire\V1\Admin\User\Supervisor\DetailsSupervisor::class)->name("administrar.v1.usuarios.supervisores.detalles");
                        Route::get('editar/{supervisor}', Livewire\V1\Admin\User\Supervisor\EditSupervisor::class)->name("administrar.v1.usuarios.supervisores.editar");
                        Route::get('agregar_clientes/{supervisor}', Livewire\V1\Admin\User\Supervisor\AddClientSupervisor::class)->name("administrar.v1.usuarios.supervisores.agregar_clientes");
                    });


                    Route::prefix("tecnico")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Technician\IndexTechnician::class)->name("administrar.v1.usuarios.tecnicos.listado");
                        Route::get('agregar', Livewire\V1\Admin\User\Technician\AddTechnician::class)->name("administrar.v1.usuarios.tecnicos.agregar");
                        Route::get('detalle/{technician}', Livewire\V1\Admin\User\Technician\DetailsTechnician::class)->name("administrar.v1.usuarios.tecnicos.detalles");
                        Route::get('editar/{technician}', Livewire\V1\Admin\User\Technician\EditTechnician::class)->name("administrar.v1.usuarios.tecnicos.editar");
                        Route::get('agregar_clientes/{technician}', Livewire\V1\Admin\User\Technician\AddClientTechnician::class)->name("administrar.v1.usuarios.tecnicos.agregar_clientes");
                        Route::get('agregar_equipos/{technician}', Livewire\V1\Admin\User\Technician\AddEquipmentTechnician::class)->name("administrar.v1.usuarios.tecnicos.agregar_equipos");
                    });


                    Route::prefix("soporte")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Support\IndexSupport::class)->name("administrar.v1.usuarios.soporte.listado");
                        Route::get('agregar', Livewire\V1\Admin\User\Support\AddSupport::class)->name("administrar.v1.usuarios.soporte.agregar");
                        Route::get('detalle/{support}', Livewire\V1\Admin\User\Support\DetailsSupport::class)->name("administrar.v1.usuarios.soporte.detalles");
                        Route::get('editar/{support}', Livewire\V1\Admin\User\Support\EditSupport::class)->name("administrar.v1.usuarios.soporte.editar");
                        Route::get('agregar_clientes/{support}', Livewire\V1\Admin\User\Support\AddClientSupport::class)->name("administrar.v1.usuarios.soporte.agregar_clientes");
                    });
                });
                Route::prefix("clientes")->group(function () {
                    Route::get('agregar', AddClient::class)->name('v1.admin.client.add.client');
                    Route::get('listado', IndexClient::class)->name("v1.admin.client.list.client");
                    Route::get('detalle/{client}', DetailClient::class)->name("v1.admin.client.detail.client");
                    Route::get('editar/{client}', EditClient::class)->name("v1.admin.client.edit.client");
                    Route::get('monitoreo/{client}', Livewire\V1\Admin\Client\Monitoring::class)->name("v1.admin.client.monitoring");
                    Route::get('configuraciones/{client}', Livewire\V1\Admin\Client\ConfigurationClient::class)->name("v1.admin.client.settings");
                    Route::get('agregar_tecnicos/{client}', Livewire\V1\Admin\Client\AddClientTechnician::class)->name("v1.admin.client.technicians");
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
    });
});


Route::post('/broadcasting/auth', function () {
    return true;
});
