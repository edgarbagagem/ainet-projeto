<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InitialController;
use App\Http\Controllers\SessaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;


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

Route::get('/', [InitialController::class, 'index'])->name('filmes.index');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/password/reset', [UserController::class, 'validatePasswordRequest'])->name('password.validateRequest');

Route::put('/password/reset', [UserController::class, 'resetPassword'])->name('password.Reset');

//Route::post('/password/reset', [UserController::class, 'send_email_with_notification'])->name('email.send_with_notification');

//Route::post('/password/reset', [UserController::class, ''])

Route::get('/sessoes', [SessaoController::class, 'index'])->name('sessoes.index');

Route::get('/sessoes/{id}', [SessaoController::class, 'sessoesFilme'])->name('sessoes.filme');

Route::get('/index/perfil', [UserController::class, 'index'])->name('index.user');

Route::get('/index/perfil/editPerfil', [UserController::class, 'editPerfil'])->name('index.user.editPerfil');

Route::put('/index/perfil/updatePerfil', [UserController::class, 'updatePerfil'])->name('index.user.updatePerfil');

Route::get('/editPassword', [UserController::class, 'editPassword'])->name('user.editPassword');

Route::put('/updatePassword', [UserController::class, 'updatePassword'])->name('user.updatePassword');


//administração de clientes
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index')
    ->middleware('can:viewAny,App\Models\Cliente');

Route::delete('/clientes/{cliente}', [ClienteController::class, 'delete'])->name('clientes.delete')
    ->middleware('can:delete, App\Models\Cliente');

Route::put('/clientes/{cliente}', [ClienteController::class, 'blockunblock'])->name('clientes.blockunblock')
    ->middleware('can:update,cliente');
