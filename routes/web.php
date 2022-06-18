<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InitialController;
use App\Http\Controllers\SessaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\EstatisticaController;

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
//configuracao
Route::put('/config', [ConfigurationController::class, 'save_config'])->name('config.save')->middleware('admin');
//salas
Route::get('/config/create/sala', [ConfigurationController::class, 'create_sala'])->name('config.create.sala')->middleware('admin');
Route::post('/config/sala/store', [ConfigurationController::class, 'store_sala'])->name('config.store.sala')->middleware('admin');
Route::get('/config/sala/edit/{sala}', [ConfigurationController::class, 'edit_sala'])->name('config.edit.sala')->middleware('admin');
Route::put('/config/sala/edit/{sala}', [ConfigurationController::class, 'update_sala'])->name('config.update.sala')->middleware('admin');
Route::delete('/config/sala/{sala}', [ConfigurationController::class, 'delete_sala'])->name('config.delete.sala')->middleware('admin');
//filme
Route::get('/config/create/filme', [ConfigurationController::class, 'create_filme'])->name('config.create.filme')->middleware('admin');
Route::post('/config', [ConfigurationController::class, 'store_filme'])->name('config.store.filme')->middleware('admin');
Route::delete('/config/filme/{filme}', [ConfigurationController::class, 'delete_filme'])->name('config.delete.filme')->middleware('admin');
Route::get('/config/filme/edit/{filme}', [ConfigurationController::class, 'edit_filme'])->name('config.edit.filme')->middleware('admin');
Route::put('/config/filme/edit/{filme}', [ConfigurationController::class, 'update_filme'])->name('config.update.filme')->middleware('admin');
//sessoes
Route::get('/config/create/sessao/{id}', [ConfigurationController::class, 'create_sessao'])->name('config.create.sessao')
    ->middleware('can:create, App\Models\Sessao');
Route::post('/config/sessao/store/{id}', [ConfigurationController::class, 'store_sessao'])->name('config.store.sessao')
    ->middleware('can:create, App\Models\Sessao');
Route::get('/config/sessao/edit/{sessao}/{id}', [ConfigurationController::class, 'edit_sessao'])->name('config.edit.sessao')
    ->middleware('can:update, App\Models\Sessao');
Route::put('/config/sessao/edit/{id}', [ConfigurationController::class, 'update_sessao'])->name('config.update.sessao')
    ->middleware('can:update, App\Models\Sessao');
Route::delete('/config/sessao/{sessao}', [ConfigurationController::class, 'delete_sessao'])->name('config.delete.sessao')
    ->middleware('can:delete, App\Models\Sessao');
//carrinho
Route::get('carrinho', [CartController::class, 'index'])->name('carrinho.index');
Route::post('carrinho/sessao/{sessao}', [CartController::class, 'store_bilhete'])->name('carrinho.store_bilhete');
Route::delete('carrinho', [CartController::class, 'destroy'])->name('carrinho.destroy');
Route::post('carrinho/payment', [CartController::class, 'preparePayment'])->name('carrinho.preparePayment');
Route::delete('carrinho/sessao/{sessao}', [CartController::class, 'destroy_sessao'])->name('carrinho.destroy_sessao');
Route::put('carrinho/sessao/{sessao}', [CartController::class, 'update_sessao'])->name('carrinho.update_sessao');
Route::post('carrinho', [CartController::class, 'store'])->name('carrinho.store');

//Controlo de Sessão
Route::get('/controloSessao', [UserController::class, 'sessionControl'])->name('controloSessao.index'); //ver se é preciso middleware c eddy
Route::get('/controloSessao/bilhetes/{id}/{bilhete_id?}/{cliente_id?}', [UserController::class, 'controlledSession'])->name('controloSessao.sessao');
Route::put('/controloSessao/bilhetes/{sessao}', [UserController::class, 'validateTickets'])->name('controloSessao.validate');


//Estatísticas
Route::get('/estatisticas', [EstatisticaController::class, 'index'])->name('estatisticas.index');


//historico
Route::get('/recibos/{user}', [ClienteController::class, 'cliente_recibos'])->name('cliente.recibos');
Route::get('recibos/{user}/{recibo}', [ClienteController::class, 'cliente_recibo'])->name('cliente.recibo');
Route::get('recibos/pdf/{user}/{recibo}', [ClienteController::class, 'cliente_recibo_pdf'])->name('cliente.recibo.pdf');
Route::get('bilhetes/{user}', [ClienteController::class, 'cliente_bilhetes'])->name('cliente.bilhetes');
Route::get('bilhetes/{user}/{bilhete}', [ClienteController::class, 'cliente_bilhete'])->name('cliente.bilhete');
Route::get('bilhetes/pdf/{user}/{bilhete}', [ClienteController::class, 'cliente_bilhete_pdf'])->name('cliente.bilhete.pdf');
Route::get('recibos/{user}/{recibo}/bilhetes', [ClienteController::class, 'cliente_recibo_bilhetes'])->name('cliente.recibo.bilhetes');
