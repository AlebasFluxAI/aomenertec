<?php

use App\Http\Controllers\testFile;
use App\Http\Controllers\V1\HomeController;
use App\Http\Controllers\V1\MailTestController;
use App\Http\Livewire;
use App\Http\Livewire\Index;
use App\Http\Livewire\V1\Admin\User\AddUser;
use App\Http\Livewire\V1\Admin\User\EditUser;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Http\Services\V1\Admin\Client\EditClient;
use App\Http\Services\V1\Admin\Client\IndexClient;
use App\Http\Services\V1\Admin\Client\DetailClient;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Resources\V1\Permissions;
use App\Http\Resources\V1\PermissionsRouteWard;

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
Route::domain("{subdomain}.enerteclatam.com")->group(function () {
    Route::get('/', '\App\Http\Controllers\V1\IndexController@index');


    Route::prefix("clientes/invitados/pqr")->group(function () {
        Route::get('/crear', Livewire\V1\Admin\Pqr\AddPqrGuestClientComponent::class)->name("guest.add-pqr");
        Route::get('/administrar', Livewire\V1\Admin\Pqr\AdminPqrGuestClientComponent::class)->name("guest.admin-pqr");
        Route::get('/creado/{pqr}', Livewire\V1\Admin\Pqr\CreatedPqrGuestClientComponent::class)->name("guest.created-pqr");
        Route::get('/administrar/{pqr}', Livewire\V1\Admin\Pqr\DetailsPqrGuestClientComponent::class)->name("guest.details-pqr");
        Route::get('/historial/{pqr}', Livewire\V1\Admin\Pqr\HistoricalPqrGuestClientComponent::class)->name("historical.details-pqr");
    });

    Route::prefix("clientes/invitados/recargas")->group(function () {
        Route::get('/crear', Livewire\V1\Admin\Purchase\PurchaseGuestCreateComponent::class)->name("guest.add-purchase");
    });

    Route::prefix("reestablecer-cuenta")->group(function () {
        Route::get('/', Livewire\V1\Admin\User\ResetPassword\ResetPassword::class)->name("subdomain.password.reset.form");
        Route::get('/{otp}', Livewire\V1\Admin\User\ResetPassword\ResetPasswordReset::class)->name("subdomain.password.reset.reset");
    });
});

Route::prefix("reestablecer-cuenta")->group(function () {
    Route::get('/', Livewire\V1\Admin\User\ResetPassword\ResetPassword::class)->name("password.reset.form");
    Route::get('/{otp}', Livewire\V1\Admin\User\ResetPassword\ResetPasswordReset::class)->name("password.reset.reset");
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('login');


Route::post('', [testFile::class, 'upload']);

Route::get('error', Livewire\V1\Admin\Error\ErrorHandler::class)->name("error.handler");

Route::get('/', function () {
    if (Auth::user()) {
        return redirect()->route("administrar.v1.perfil");
    }
    return view('auth.login');
});

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get("mail/test/user_created", (MailTestController::class) . "@userCreatedNotification");
Route::get("mail/test/whatsapp_created", (MailTestController::class) . "@whatsappNotification");

Route::group(['middleware' => ['auth:sanctum', 'verified', 'enable_user']], function () {

    Route::get('/seleccionar_rol', Livewire\V1\Admin\User\SelectRoleUser::class)->name("administrar.v1.seleccionar_role");
});

Route::group(['middleware' => ['auth:sanctum', 'verified', 'enable_user', "role_selection"]], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::prefix("v1")->group(function () {
        Route::get('/inicio', Livewire\V1\Admin\User\ProfileUser::class)->name("administrar.v1.perfil");
        Route::get('/notificaciones', Livewire\V1\Admin\User\Notification\NotificationComponent::class)->name("administrar.v1.notificaciones");
        Route::get('/persmisos/tabs', Livewire\V1\Admin\User\TabPermission::class)->name("administrar.v1.permisos.pestanas");
        Route::prefix("administrar")->group(function () {
            Route::middleware([])->group(function () {
                Route::prefix("usuarios")->group(function () {
                    Route::get('agregar', AddUser::class)->name("administrar.v1.usuarios.agregar");
                    Route::get('editar', EditUser::class)->name("administrar.v1.usuarios.editar");

                    Route::prefix("super_administrador")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\SuperAdmin\IndexSuperAdmin::class)
                            ->name("administrar.v1.usuarios.superadmin.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPER_ADMIN_SHOW));
                        Route::get('agregar', Livewire\V1\Admin\User\SuperAdmin\AddSuperAdmin::class)
                            ->name("administrar.v1.usuarios.superadmin.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPER_ADMIN_CREATE));
                        Route::get('detalle/{superAdmin}', Livewire\V1\Admin\User\SuperAdmin\DetailsSuperAdmin::class)
                            ->name("administrar.v1.usuarios.superadmin.detalles")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPER_ADMIN_SHOW));
                        Route::get('editar/{superAdmin}', Livewire\V1\Admin\User\SuperAdmin\EditSuperAdmin::class)
                            ->name("administrar.v1.usuarios.superadmin.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPER_ADMIN_EDIT));
                    });

                    Route::prefix("administrador")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Admin\IndexAdmin::class)
                            ->name("administrar.v1.usuarios.admin.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::ADMIN_SHOW));

                        Route::get('agregar', Livewire\V1\Admin\User\Admin\AddAdmin::class)
                            ->name("administrar.v1.usuarios.admin.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::ADMIN_CREATE));

                        Route::get('editar/{admin}', Livewire\V1\Admin\User\Admin\EditAdmin::class)
                            ->name("administrar.v1.usuarios.admin.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::ADMIN_EDIT));

                        Route::get('detalle/{admin}', Livewire\V1\Admin\User\Admin\DetailsAdmin::class)
                            ->name("administrar.v1.usuarios.admin.detalles")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::ADMIN_SHOW));

                        Route::get('agregar_tipos_equipos/{admin}', Livewire\V1\Admin\User\Admin\AddEquipmentTypeAdmin::class)
                            ->name("administrar.v1.usuarios.admin.agregar_tipos_equipo")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::ADMIN_LINK_EQUIPMENT_TYPE));

                        Route::get('agregar_equipos/{admin}', Livewire\V1\Admin\User\Admin\AddEquipmentAdmin::class)
                            ->name("administrar.v1.usuarios.admin.agregar_equipos")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::ADMIN_LINK_EQUIPMENT));

                        Route::get('precio_administracion/{admin}', Livewire\V1\Admin\User\Admin\PriceAdmin::class)
                            ->name("administrar.v1.usuarios.admin.editar_precios");
                    });


                    Route::prefix("operador")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\NetworkOperator\IndexNetworkOperator::class)
                            ->name("administrar.v1.usuarios.operadores.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::NETWORK_OPERATOR_SHOW));

                        Route::get('agregar', Livewire\V1\Admin\User\NetworkOperator\AddNetworkOperator::class)
                            ->name("administrar.v1.usuarios.operadores.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::NETWORK_OPERATOR_CREATE));

                        Route::get('editar/{networkOperator}', Livewire\V1\Admin\User\NetworkOperator\EditNetworkOperator::class)
                            ->name("administrar.v1.usuarios.operadores.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::NETWORK_OPERATOR_EDIT));

                        Route::get('detalle/{networkOperator}', Livewire\V1\Admin\User\NetworkOperator\DetailsNetworkOperator::class)
                            ->name("administrar.v1.usuarios.operadores.detalles")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::NETWORK_OPERATOR_SHOW));

                        Route::get('agregar_equipos/{networkOperator}', Livewire\V1\Admin\User\NetworkOperator\AddEquipmentNetworkOperator::class)
                            ->name("administrar.v1.usuarios.operadores.agregar_equipos")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::NETWORK_OPERATOR_LINK_EQUIPMENT));

                        Route::get('configurar_precios/{networkOperator}', Livewire\V1\Admin\User\NetworkOperator\PriceConfigurationNetworkOperator::class)
                            ->name("administrar.v1.usuarios.operadores.configurar_precios")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::NETWORK_OPERATOR_PRICE_CONFIGURATION));
                    });


                    Route::prefix("vendedor")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Seller\IndexSeller::class)
                            ->name("administrar.v1.usuarios.vendedores.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SELLER_SHOW));

                        Route::get('agregar', Livewire\V1\Admin\User\Seller\AddSeller::class)
                            ->name("administrar.v1.usuarios.vendedores.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SELLER_CREATE));

                        Route::get('editar/{seller}', Livewire\V1\Admin\User\Seller\EditSeller::class)
                            ->name("administrar.v1.usuarios.vendedores.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SELLER_EDIT));

                        Route::get('detalle/{seller}', Livewire\V1\Admin\User\Seller\DetailsSeller::class)
                            ->name("administrar.v1.usuarios.vendedores.detalles")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SELLER_SHOW));

                        Route::get('agregar_clientes/{seller}', Livewire\V1\Admin\User\Seller\AddClientSeller::class)
                            ->name("administrar.v1.usuarios.vendedores.agregar_clientes")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SELLER_LINK_CLIENT));

                        Route::get('{seller}/recargas/crear', Livewire\V1\Admin\Purchase\PurchaseCreateComponent::class)
                            ->name("administrar.v1.usuarios.vendedores.recargas.crear")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SELLER_MANAGE_PURCHASE_CREATE));

                        Route::get('{seller}/recargas/historico', Livewire\V1\Admin\Purchase\PurchaseHistoricalComponent::class)
                            ->name("administrar.v1.usuarios.vendedores.recargas.historico")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SELLER_MANAGE_PURCHASE));
                    });


                    Route::prefix("supervisor")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Supervisor\IndexSupervisor::class)
                            ->name("administrar.v1.usuarios.supervisores.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPERVISOR_SHOW));

                        Route::get('agregar', Livewire\V1\Admin\User\Supervisor\AddSupervisor::class)
                            ->name("administrar.v1.usuarios.supervisores.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPERVISOR_CREATE));

                        Route::get('detalle/{supervisor}', Livewire\V1\Admin\User\Supervisor\DetailsSupervisor::class)
                            ->name("administrar.v1.usuarios.supervisores.detalles")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPERVISOR_SHOW));

                        Route::get('editar/{supervisor}', Livewire\V1\Admin\User\Supervisor\EditSupervisor::class)
                            ->name("administrar.v1.usuarios.supervisores.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPERVISOR_EDIT));

                        Route::get('agregar_clientes/{supervisor}', Livewire\V1\Admin\User\Supervisor\AddClientSupervisor::class)
                            ->name("administrar.v1.usuarios.supervisores.agregar_clientes")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPERVISOR_LINK_CLIENT));
                    });


                    Route::prefix("tecnico")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Technician\IndexTechnician::class)
                            ->name("administrar.v1.usuarios.tecnicos.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TECHNICIAN_SHOW));

                        Route::get('agregar', Livewire\V1\Admin\User\Technician\AddTechnician::class)
                            ->name("administrar.v1.usuarios.tecnicos.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TECHNICIAN_CREATE));

                        Route::get('detalle/{technician}', Livewire\V1\Admin\User\Technician\DetailsTechnician::class)
                            ->name("administrar.v1.usuarios.tecnicos.detalles")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TECHNICIAN_SHOW));

                        Route::get('editar/{technician}', Livewire\V1\Admin\User\Technician\EditTechnician::class)
                            ->name("administrar.v1.usuarios.tecnicos.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_EDIT));

                        Route::get('agregar_clientes/{technician}', Livewire\V1\Admin\User\Technician\AddClientTechnician::class)
                            ->name("administrar.v1.usuarios.tecnicos.agregar_clientes")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_SHOW));

                        Route::get('agregar_equipos/{technician}', Livewire\V1\Admin\User\Technician\AddEquipmentTechnician::class)
                            ->name("administrar.v1.usuarios.tecnicos.agregar_equipos")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TECHNICIAN_LINK_EQUIPMENT));
                    });


                    Route::prefix("soporte")->group(function () {
                        Route::get('listado', Livewire\V1\Admin\User\Support\IndexSupport::class)
                            ->name("administrar.v1.usuarios.soporte.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_SHOW));

                        Route::get('agregar', Livewire\V1\Admin\User\Support\AddSupport::class)
                            ->name("administrar.v1.usuarios.soporte.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_CREATE));

                        Route::get('detalle/{support}', Livewire\V1\Admin\User\Support\DetailsSupport::class)
                            ->name("administrar.v1.usuarios.soporte.detalles")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_SHOW));

                        Route::get('cola/ordenes_de_trabajo', Livewire\V1\Admin\User\Support\IndexWorkOrder::class)
                            ->name("administrar.v1.usuarios.soporte.cola.ordenes_de_trabajo")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_WORK_ORDER_QUEUE));

                        Route::get('editar/{support}', Livewire\V1\Admin\User\Support\EditSupport::class)
                            ->name("administrar.v1.usuarios.soporte.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_EDIT));

                        Route::get('agregar_clientes/{support}', Livewire\V1\Admin\User\Support\AddClientSupport::class)
                            ->name("administrar.v1.usuarios.soporte.agregar_clientes")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::SUPPORT_LINK_CLIENT));
                    });
                });
                Route::prefix("clientes")->group(function () {
                    Route::get('agregar', Livewire\V1\Admin\Client\AddClient::class)
                        ->name('v1.admin.client.add.client')
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_CREATE));

                    Route::get('listado', Livewire\V1\Admin\Client\IndexClient::class)
                        ->name("v1.admin.client.list.client")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_SHOW));

                    Route::get('detalle/{client}', Livewire\V1\Admin\Client\DetailClient::class)
                        ->name("v1.admin.client.detail.client")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_SHOW));

                    Route::get('editar/{client}', Livewire\V1\Admin\Client\EditClient::class)
                        ->name("v1.admin.client.edit.client")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_EDIT));

                    Route::get('equipos/{client}', Livewire\V1\Admin\Client\AddEquipmentToClient::class)
                        ->name("v1.admin.client.add.equipment")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_ADD_EQUIPMENT));

                    Route::get('alertas/{client}', Livewire\V1\Admin\Client\ClientAlertIndex::class)
                        ->name("v1.admin.client.add.alerts")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_SHOW_ALERTS));

                    Route::get('monitoreo/{client}', Livewire\V1\Admin\Client\Monitoring::class)
                        ->name("v1.admin.client.monitoring")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_SHOW_MONITORING));

                    Route::get('monitoreo/{client}/control', Livewire\V1\Admin\Client\Monitoring\Control::class)
                        ->name("v1.admin.client.monitoring.control")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_MONITORING_CONTROL));

                    Route::get('configuraciones/{client}', Livewire\V1\Admin\Client\ConfigurationClient::class)
                        ->name("v1.admin.client.settings")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_SETTINGS));

                    Route::get('ordenes_de_trabajo/{client}', Livewire\V1\Admin\Client\WorkOrderClient::class)
                        ->name("v1.admin.client.work_orders")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::WORK_ORDER_SHOW));

                    Route::get('ordenes_de_trabajo/{client}/crear', Livewire\V1\Admin\Client\WorkOrderClientCreate::class)
                        ->name("v1.admin.client.work_orders.create")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::WORK_ORDER_CREATE));

                    Route::get('historico_cambio_equipo/{client}', Livewire\V1\Admin\Client\ClientEquipmentChangeHistorical::class)
                        ->name("v1.admin.client.change_equipment.historical")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_SHOW));
                });

                Route::prefix("clientes-desactivados")->group(function () {
                    Route::get('listado', Livewire\V1\Admin\Client\ClientDisabled\IndexClient::class)
                        ->name("v1.admin.client-disabled.list.client")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_DISABLED_SHOW));

                    Route::get('detalle/{client}', Livewire\V1\Admin\Client\ClientDisabled\DetailClient::class)
                        ->name("v1.admin.client-disabled.detail.client")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::CLIENT_DISABLED_SHOW));
                });

                Route::prefix("ordenes_de_servicio")->group(function () {
                    Route::get('', Livewire\V1\Admin\WorkOrder\WorkOrderIndex::class)
                        ->name("administrar.v1.ordenes_de_servicio.listado")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::WORK_ORDER_INDEX));

                    Route::get('detalle/{workOrder}', Livewire\V1\Admin\WorkOrder\WorkOrderDetails::class)
                        ->name("administrar.v1.ordenes_de_servicio.detalle")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::WORK_ORDER_DETAILS));

                    Route::get('editar/{workOrder}', Livewire\V1\Admin\WorkOrder\WorkOrderEdit::class)
                        ->name("administrar.v1.ordenes_de_servicio.editar")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::WORK_ORDER_EDIT));

                    Route::get('administrar/{workOrder}', Livewire\V1\Admin\WorkOrder\WorkOrderSolver::class)
                        ->name("administrar.v1.ordenes_de_servicio.administrar")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::WORK_ORDER_SOLVE));
                });
                Route::prefix("equipos")->group(function () {
                    Route::get('agregar', Livewire\V1\Admin\Equipment\AddEquipment::class)
                        ->name("administrar.v1.equipos.agregar")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_CREATE));

                    Route::get('listado', Livewire\V1\Admin\Equipment\IndexEquipment::class)
                        ->name("administrar.v1.equipos.listado")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_SHOW));

                    Route::get('detalle/{equipment}', Livewire\V1\Admin\Equipment\DetailEquipment::class)
                        ->name("administrar.v1.equipos.detalle")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_SHOW));

                    Route::get('editar/{equipment}', Livewire\V1\Admin\Equipment\EditEquipment::class)
                        ->name("administrar.v1.equipos.editar")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_EDIT));

                    Route::prefix("tipos")->group(function () {
                        Route::get('agregar', Livewire\V1\Admin\EquipmentType\AddEquipmentType::class)
                            ->name("administrar.v1.equipos.tipos.agregar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_CREATE));

                        Route::get('listado', Livewire\V1\Admin\EquipmentType\IndexEquipmentType::class)
                            ->name("administrar.v1.equipos.tipos.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_SHOW));

                        Route::get('detalle/{equipmentType}', Livewire\V1\Admin\EquipmentType\DetailEquipmentType::class)
                            ->name("administrar.v1.equipos.tipos.detalle")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_SHOW));

                        Route::get('editar/{equipmentType}', Livewire\V1\Admin\EquipmentType\EditEquipmentType::class)
                            ->name("administrar.v1.equipos.tipos.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::EQUIPMENT_EDIT));
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

                Route::prefix("facturacion")->group(function () {
                    Route::prefix("impuestos")->group(function () {
                        Route::get("", Livewire\V1\Admin\Invoicing\Tax\TaxIndexComponent::class)
                            ->name("administrar.v1.facturacion.impuestos.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TAX_INDEX));

                        Route::get("crear", Livewire\V1\Admin\Invoicing\Tax\TaxAddComponent::class)
                            ->name("administrar.v1.facturacion.impuestos.crear")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TAX_CREATE));

                        Route::get("editar/{tax}", Livewire\V1\Admin\Invoicing\Tax\TaxEditComponent::class)
                            ->name("administrar.v1.facturacion.impuestos.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TAX_EDIT));


                        Route::get("detalles/{tax}", Livewire\V1\Admin\Invoicing\Tax\TaxDetailsComponent::class)
                            ->name("administrar.v1.facturacion.impuestos.detalle")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::TAX_SHOW));
                    });
                    Route::prefix("items_facturables")->group(function () {
                        Route::get("", Livewire\V1\Admin\Invoicing\BillableItems\BillableItemsIndexComponent::class)
                            ->name("administrar.v1.facturacion.items.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::BILLABLE_ITEMS_INDEX));

                        Route::get("crear", Livewire\V1\Admin\Invoicing\BillableItems\BillableItemsAddComponent::class)
                            ->name("administrar.v1.facturacion.items.crear")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::BILLABLE_ITEMS_CREATE));

                        Route::get("editar/{billable_item}", Livewire\V1\Admin\Invoicing\BillableItems\BillableItemsEditComponent::class)
                            ->name("administrar.v1.facturacion.items.editar")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::BILLABLE_ITEMS_EDIT));


                        Route::get("detalles/{billable_item}", Livewire\V1\Admin\Invoicing\BillableItems\BillableItemsDetailsComponent::class)
                            ->name("administrar.v1.facturacion.items.detalle")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::BILLABLE_ITEMS_SHOW));
                    });

                    Route::prefix("facturas")->group(function () {
                        Route::get("", Livewire\V1\Admin\Invoicing\Invoice\InvoiceIndexComponent::class)
                            ->name("administrar.v1.facturacion.facturas.listado")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::INVOICE_INDEX));

                        Route::get("detalles/{invoice}", Livewire\V1\Admin\Invoicing\Invoice\InvoiceDetailsComponent::class)
                            ->name("administrar.v1.facturacion.facturas.detalle")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::INVOICE_SHOW));

                        Route::get("pdf/{invoice}", [Livewire\V1\Admin\Invoicing\Invoice\InvoicePdfGeneratorController::class, "getPdf"])
                            ->name("administrar.v1.facturacion.facturas.pdf")
                            ->middleware(PermissionsRouteWard::permissionWard(Permissions::INVOICE_FILE));
                    });

                });

                Route::prefix("peticiones")->group(function () {
                    Route::get("listado", Livewire\V1\Admin\Pqr\PqrIndexComponent::class)
                        ->name("administrar.v1.peticiones.listado")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_SHOW));

                    Route::get("detalles/{pqr}", Livewire\V1\Admin\Pqr\PqrDetailsComponent::class)
                        ->name("administrar.v1.peticiones.detalles")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_SHOW));

                    Route::get("respuesta/{pqr}", Livewire\V1\Admin\Pqr\PqrReplyComponent::class)
                        ->name("administrar.v1.peticiones.respuesta")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_REPLY));


                    Route::get("asociar_cliente/{pqr}", Livewire\V1\Admin\Pqr\PqrAddClientComponent::class)
                        ->name("administrar.v1.peticiones.relacionar_cliente")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_LINK_CLIENT));


                    Route::get("cerrar/{pqr}", Livewire\V1\Admin\Pqr\PqrCloseComponent::class)
                        ->name("administrar.v1.peticiones.cierre")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_CLOSE));

                    Route::get("historial/{pqr}", Livewire\V1\Admin\Pqr\HistoricalPqrComponent::class)
                        ->name("administrar.v1.peticiones.historial-mensajes")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_REPLY));

                    Route::get("supervisor/crear", Livewire\V1\Admin\Pqr\AddPqrSupervisorComponent::class)
                        ->name("administrar.v1.peticiones.supervisor.crear")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_CREATE));

                    Route::get("operador/crear", Livewire\V1\Admin\Pqr\AddPqrNetworkOperatorComponent::class)
                        ->name("administrar.v1.peticiones.operador.crear")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_CREATE_NETWORK_OPERATOR));

                    Route::get("cambio_de_equipo/{pqr}", Livewire\V1\Admin\Pqr\PqrChangeEquipmentManageComponent::class)
                        ->name("administrar.v1.peticiones.cambio-equipo")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_EQUIPMENT_CHANGE_MANAGE));

                    Route::get("historico_cambio_de_equipo/{pqr}", Livewire\V1\Admin\Pqr\PqrChangeEquipmentHistoryComponent::class)
                        ->name("administrar.v1.peticiones.cambio-equipo-historico")
                        ->middleware(PermissionsRouteWard::permissionWard(Permissions::PQR_EQUIPMENT_CHANGE_MANAGE));
                });
            });
        });
    });
});


Route::post('/broadcasting/auth', function () {
    return true;
});
