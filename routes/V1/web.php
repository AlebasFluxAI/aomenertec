<?php

use App\Http\Controllers\V1\HomeController;
use App\Http\Livewire;
use App\Http\Livewire\Index;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\V1\Admin\AddUser;
use App\Http\Livewire\V1\Admin\EditUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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


Route::middleware([ 'permission:add_user','permission:edit_user'])->group(function () {
    Route::prefix("v1")->group(function () {
        Route::prefix("administrar")->group(function () {
            Route::get('usuarios/agregar', AddUser::class);
            Route::get('usuarios/editar', EditUser::class);
        });
    });
});
