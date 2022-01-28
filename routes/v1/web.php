<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\HomeController;
use App\Http\Livewire;

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

Route::get('/', function () {
    return view('auth.v1.login');
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});


Route::get('/administrar/v1', Livewire\Administrar\v1\Index::class)->name('administrar');
Route::group(['middleware' => ['permission:add_user']], function () {
    Route::get('/administrar/v1/usuarios/agregar', Livewire\Administrar\v1\AddUser::class)->name('administrar.adduser');
});
Route::get('/administrar/v1/usuarios/editar', Livewire\Administrar\v1\EditUser::class)->name('administrar.edituser')->middleware('permission:edit_user');
