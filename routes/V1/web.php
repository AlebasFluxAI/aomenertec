<?php

use App\Http\Controllers\V1\HomeController;
use App\Http\Livewire;
use App\Http\Livewire\Index;
use App\Http\Livewire\V1\Admin\User\AddUser;
use App\Http\Livewire\V1\Admin\User\EditUser;
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
Route::post('', [\App\Http\Controllers\testFile::class,'upload']);

Route::get('/v1/login', function () {
    return view('auth.v1.login');
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::prefix("v1")->group(function () {
    Route::prefix("administrar")->group(function () {
        Route::get('/', Index::class);
    });
});


Route::middleware([])->group(function () {
    Route::prefix("v1")->group(function () {
        Route::prefix("administrar")->group(function () {
            Route::prefix("usuarios")->group(function () {
                Route::get('agregar', AddUser::class);
                Route::get('editar', EditUser::class)->name("administrar.edituser");
            });
            Route::prefix("equipos")->group(function () {
                Route::get('agregar', Livewire\V1\Admin\Equipment\AddEquipment::class)->name("administrar.equipos.agregar");
                Route::get('listado', Livewire\V1\Admin\Equipment\IndexEquipment::class)->name("administrar.equipos.listado");
                Route::get('editar/{equipment}', Livewire\V1\Admin\Equipment\EditEquipment::class)->name("administrar.equipos.editar");
            });
        });
    });
});

Route::post('/broadcasting/autha', function () {
    return true;
});
