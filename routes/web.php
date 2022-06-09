<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InitialController;
use App\Http\Controllers\SessaoController;
use App\Http\Controllers\UserController;


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
