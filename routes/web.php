<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InitialController;
use App\Http\Controllers\SessaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConfigurationController;


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

//administração de a/f

Route::get('/admininistracao', [UserController::class, 'admin'])->name('users.admin')->middleware('can:viewAny, App\Models\User');

Route::delete('/administracao/{user}', [UserController::class, 'admin_delete'])->name('users.admin.delete')->middleware('can:delete, App\Models\User');

Route::put('/administracao/{user}', [UserController::class, 'admin_blockunblock'])->name('users.admin.blockunblock')
    ->middleware('can:update, App\Models\User');

Route::get('/admininistracao/perfil/{user}', [UserController::class, 'admin_consultar'])->name('users.admin.consultar')->middleware('can:view, App\Models\User');

Route::get('/admininistracao/perfil/edit/{user}', [UserController::class, 'admin_editar'])->name('users.admin.edit')->middleware('can:view, App\Models\User');

Route::put('/administracao/perfil/edit/{user}', [UserController::class, 'admin_updateUser'])->name('users.admin.update')
    ->middleware('can:update, App\Models\User');

Route::get('/admininistracao/create', [UserController::class, 'admin_create'])->name('users.admin.create')->middleware('can:create, App\Models\User');

Route::post('/administracao', [UserController::class, 'admin_store'])->name('users.admin.store')
    ->middleware('can:create,App\Models\User');

//administracao negocio

Route::get('/config', [ConfigurationController::class, 'index'])->name('config.index')->middleware('admin');
Route::put('/config', [ConfigurationController::class, 'save_config'])->name('config.save')->middleware('admin');
Route::get('/config/create_sala', [ConfigurationController::class, 'create_sala'])->name('config.create.sala')->middleware('admin');
Route::post('/config', [ConfigurationController::class, 'store_sala'])->name('config.store.sala')->middleware('admin');
Route::get('/config/edit/{sala}', [ConfigurationController::class, 'edit_sala'])->name('config.edit.sala')->middleware('admin');
Route::put('/config/edit/{sala}', [ConfigurationController::class, 'update_sala'])->name('config.update.sala')->middleware('admin');
Route::delete('/config/{sala}', [ConfigurationController::class, 'delete_sala'])->name('config.delete.sala')->middleware('admin');

//carrinho
Route::get('/carrinho/{id}', [CartController::class, 'index'])->name('add.cart');


//Controlo de Sessão
Route::get('/controloSessao', [UserController::class, 'sessionControl'])->name('controloSessao.index'); //ver se é preciso middleware c eddy
Route::get('/controloSessao/bilhetes/{id}', [UserController::class, 'controlledSession'])->name('controloSessao.sessao'); 
Route::put('/controloSessao/bilhetes/{id}', [UserController::class, 'validateTickets'])->name('controloSessao.validate');